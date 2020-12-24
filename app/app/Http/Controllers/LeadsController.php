<?php


namespace App\Http\Controllers;


use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Collections\Leads\LeadsCollection;
use AmoCRM\Collections\LinksCollection;
use AmoCRM\Filters\LeadsFilter;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\CompanyModel;
use AmoCRM\Models\ContactModel;
use AmoCRM\Models\LeadModel;
use AmoCRM\Models\NoteType\ServiceMessageNote;
use AmoCRM\Models\TaskModel;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LeadsController extends Controller
{

    public function show(AmoCRMApiClient $client): Response
    {
        $filter = new LeadsFilter();
        $leadsService = $client->leads()->get($filter, [LeadModel::CONTACTS]);
        $contactService = $client->contacts();
        $output = [];
        foreach ($leadsService as $lead) {
            $temp['name'] = $lead->getName();
            $temp['resusr'] = $client->users()->getOne($lead->getResponsibleUserId())->getName();
            if ($lead->getContacts()) {
                foreach ($lead->getContacts() as $contact) {
                    $contactData = $contactService->getOne($contact->id);
                    $temp['contacts'][$contactData->getId()]['name'] = $contactData->getName();
                    foreach ($contactData->getCustomFieldsValues() as $customFieldsValue) {
                        if ($customFieldsValue->getFieldCode() === 'PHONE') {
                            $temp['contacts'][$contactData->getId()]['phone'] = $customFieldsValue->getValues()[0]->value;
                        }
                    }
                }
            }
            $output['leads'][$lead->id] = $temp;
        }
        return response()->view('leads.index', $output);
    }

    public function createCompany(AmoCRMApiClient $client): View
    {
        return view('create.company', ['leads' => $client->leads()->get()]);
    }

    public function saveCompany(Request $request, AmoCRMApiClient $client): RedirectResponse
    {
        $company = new CompanyModel();
        $company->setName($request->input('company_name'));
        $lead = $client->leads()->getOne($request->input('lead_id'));
        $company->setLeads(LeadsCollection::make([$lead]));
        $client->companies()->addOne($company);
        return response()->redirectToRoute('home');
    }

    public function createContact(AmoCRMApiClient $client): Response
    {
        return response()->view('create.contact',
            ['companies' => $client->companies()->get(), 'leads' => $client->leads()->get()]);
    }

    public function saveContact(Request $request, AmoCRMApiClient $client): RedirectResponse
    {
        $contact = new ContactModel();
        $contact->setName($request->input('contact_name'));
        $contact->setCompany($client->companies()->getOne($request->input('company_id')));
        $lead = $client->leads()->getOne($request->input('lead_id'));
        $contact->setLeads(LeadsCollection::make([$lead]));
        $client->contacts()->addOne($contact);

        return response()->redirectToRoute('home');
    }

    public function createNote(AmoCRMApiClient $client): Response
    {
        return response()->view('create.note', ['leads' => $client->leads()->get()]);
    }

    public function saveNote(Request $request, AmoCRMApiClient $client): RedirectResponse
    {
        $note = new ServiceMessageNote();
        $note->setService('Task');
        $note->setText($request->input('note_text'));
        $note->setEntityId($request->input('lead_id'));
        $client->notes(EntityTypesInterface::LEADS)->addOne($note);

        return response()->redirectToRoute('home');
    }

    public function createTask(AmoCRMApiClient $client): Response
    {
        return response()->view('create.task', ['leads' => $client->leads()->get()]);
    }

    public function saveTask(Request $request, AmoCRMApiClient $client): RedirectResponse
    {
        $task = new TaskModel();
        $lead = $client->leads()->getOne($request->input('lead_id'));
        $task->setResponsibleUserId($lead->getResponsibleUserId());
        $task->setTaskTypeId(TaskModel::TASK_TYPE_ID_MEETING);
        $task->setText($request->input('task_text'));
        $task->setCompleteTill(Carbon::tomorrow()->getTimestamp());
        $client->tasks()->addOne($task);
        return response()->redirectToRoute('home');
    }

    public function createLead(): Response
    {
        return response()->view('create.lead');
    }

    public function saveLead(Request $request, AmoCRMApiClient $client): RedirectResponse
    {
        $lead = new LeadModel();
        $lead->setName($request->input('lead_name'));
        $lead->setResponsibleUserId($client->account()->getCurrent()->getCurrentUserId());
        $client->leads()->add(LeadsCollection::make([$lead]));
        return response()->redirectToRoute('home');
    }

    public function processTaskPoints( AmoCRMApiClient $client): JsonResponse
    {
        $lead = new LeadModel();
        $lead->setName("Произвольная тестовоая сделка");
        $lead->setResponsibleUserId($client->account()->getCurrent()->getCurrentUserId());
        $client->leads()->add(LeadsCollection::make([$lead]));

        $company = new CompanyModel();
        $company->setName("Рандомная окмпания");
        $client->companies()->addOne($company);

        $contact = new ContactModel();
        $contact->setName("Рандомный контакт");
        $client->contacts()->addOne($contact);

        $task = new TaskModel();
        $task->setResponsibleUserId($lead->getResponsibleUserId());
        $task->setTaskTypeId(TaskModel::TASK_TYPE_ID_MEETING);
        $task->setText("Рандомный ТАСК");
        $task->setCompleteTill(Carbon::tomorrow()->getTimestamp());
        $client->tasks()->addOne($task);

        $note = new ServiceMessageNote();
        $note->setService("API_TASK");
        $note->setText("Произвольный текст");
        $note->setEntityId($lead->getId());
        $client->notes(EntityTypesInterface::LEADS)->addOne($note);

        $client->contacts()->link($contact, LinksCollection::make([$company]));
        $client->leads()->link($lead, LinksCollection::make([$company, $contact]));

        return response()->json($client->notes(EntityTypesInterface::LEADS)->get());
    }
}
