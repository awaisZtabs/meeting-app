<?php

namespace App\Http\Controllers;

use Google\Client;
use App\Models\Event;
use Google\Service\Calendar;
use Illuminate\Http\Request;
use Google\Service\Calendar\Calendar as CalendarEvent;

class ManageEventController extends Controller
{

    public function index()
    {
        $events = Event::where("created_by", auth()->user()->id)->paginate(5);;
        return view("events.index", compact('events'));
    }


    /* need to fetch google auth token for creating
    events on google calendar so if its not set already,
     user will be redirected to consent page and after verification
     callback url will be called. */
    public function create()
    {
        $client = $this->getClient();
        $authUrl = $client->createAuthUrl();
        if (auth()->user()->google_access_token == null) {
            return redirect($authUrl);
        }
        return view("events.create");
    }

    /* fetches user access token and save in local db for future use. */
    public function handleGoogleCallback(Request $request)
    {
        $client = $this->getClient();
        $client->fetchAccessTokenWithAuthCode($request->code);
        // Store the access token to use for API requests
        $accessToken = $client->getAccessToken();
        auth()->user()->google_access_token = $accessToken;
        return view("events.create");
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'event_subject' => 'required',
            'first_attendee_email' => 'required|email',
            'event_date' => 'required|date',
            'second_attendee_email' => 'required|email',
        ]);
        /* create event in local db */
        $newEvent = new Event;
        $newEvent->subject = $request->event_subject;
        $newEvent->first_attendee_email = $request->first_attendee_email;
        $newEvent->second_attendee_email = $request->second_attendee_email;
        $newEvent->event_date = $request->event_date;
        $newEvent->created_by = auth()->user()->id;
        $newEvent->save();
        /* create event in google calendar. */

        /* COMMENT THIS PART TO TEST NORMAL CRUD */
        $client = $this->getClient();
        $client->setAccessToken(auth()->user()->google_access_token);

        $service = new Calendar($client);
        $calendarEvent = new CalendarEvent([
            'summary' => $newEvent->subject,
            'start' => [
                'dateTime' => $newEvent->event_date,
                'timeZone' => 'Asia/Karachi',
            ],
            'end' => [
                'dateTime' => $newEvent->event_date->addDays(5),
                'timeZone' => 'Asia/Karachi',
            ],
        ]);
        $attendees = [
            ['email' => $newEvent->first_attendee_email],
            ['email' => $newEvent->second_attendee_email],
        ];
        $calendarEvent->attendees = $attendees;
        $calendarId = 'primary';
        $event = $service->events->insert($calendarId, $calendarEvent);
        return response()->json(['message' => 'Success'], 200);
    }

    public function edit($id)
    {
        $event = Event::where("id", $id)->first();
        return view("events.edit", compact('event'));
    }
    public function update(Request $request, $id)
    {
        $event = Event::where("id", $id)->first();
        $validatedData = $request->validate([
            'event_subject' => 'required',
            'first_attendee_email' => 'required|email',
            'event_date' => 'required|date',
            'second_attendee_email' => 'required|email',
        ]);
        $event->subject = $request->event_subject;
        $event->first_attendee_email = $request->first_attendee_email;
        $event->second_attendee_email = $request->second_attendee_email;
        $event->event_date = $request->event_date;
        $event->created_by = auth()->user()->id;
        $event->save();
        return response()->json(['message' => 'Success'], 200);
    }

    public function destroy($id)
    {
        Event::destroy($id);
        return response()->json(['message' => 'Success'], 200);
    }

    /* create client's instance for api usage */
    private function getClient()
    {
        $client = new Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setRedirectUri(config('services.google.redirect'));
        $client->setAccessType('offline');
        $client->setScopes(Calendar::CALENDAR);
        return $client;
    }
}
