<?php

namespace App\Http\Controllers\API\V1;

use App\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\EventStoreRequest;
use App\Http\Requests\EventUpdateRequest;
use App\Http\Resources\ApiResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    public function index()
    {
        $event = Event::latest()->get();
        return new ApiResponse(true, 'List Event', $event);
    }

    public function show($id)
    {
        $event = Event::find($id);
        if ($event) {
            return new ApiResponse(true, 'Details Event', $event);
        }
        return response()->json(new ApiResponse(false, 'Event tidak ditemukan'), 404);
    }

    public function store(EventStoreRequest $request)
    {
        $request->validated();

        $image = $request->file('image');
        $image->storeAs('image/event', $image->hashName());

        $event = Event::create([
            'title' => $request->title,
            'description' => $request->description,
            'organizer' => $request->organizer,
            'location' => $request->location,
            'date' => $request->date,
            'image' => $image->hashName(),
            // 'image' => request()->file('image')->store('image/event'),
        ]);

        return new ApiResponse(true, 'Event Berhasil Ditambahkan', $event);
    }

    public function update(EventUpdateRequest $request, $id)
    {
        $request->validated();
        $event = Event::find($id);

        if (!$event) {
            return response()->json(new ApiResponse(false, 'Data Event Tidak Ditemukan', $event), 404);
        }

        if ($request->hasFile('image')) {

            $image = $request->file('image');
            $image->storeAs('image/event', $image->hashName());

            Storage::delete('image/event/' . $event->image);

            $event->update([
                'title' => $request->title,
                'description' => $request->description,
                'organizer' => $request->organizer,
                'location' => $request->location,
                'date' => $request->date,
                'image' => $image,
            ]);
        } else {
            $image = $event->image;
            $event->update([
                'title' => $request->title,
                'description' => $request->description,
                'organizer' => $request->organizer,
                'location' => $request->location,
                'date' => $request->date,
                'image' => $image,
            ]);
        }


        return new ApiResponse(true, 'Data Event Berhasil Disunting', $event);
    }

    public function destroy($id)
    {
        $event = Event::find($id);

        Storage::delete('image/event/' . $event->image);
        Event::destroy($id);

        return new ApiResponse(true, 'Data Behasil Dihapus', null);
    }
}