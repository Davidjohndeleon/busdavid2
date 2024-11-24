<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusBooking;

class AdminBusBookingController extends Controller
{
    public function index()
    {
        // Fetch all bus bookings with related data and group by passenger
        $bookings = BusBooking::with(['bus', 'passenger'])
            ->get()
            ->groupBy('user_id');

        // Pass grouped bookings to the view
        return view('admin.bus_bookings', compact('bookings'));
    }

    public function updateStatus(Request $request, $id)
    {
        // Find the booking
        $booking = BusBooking::findOrFail($id);

                // Validate the incoming status and remarks
        $request->validate([
           'status' => 'required|in:pending,approved,rejected',
           'remarks' => 'nullable|string|max:255',
        ]);

        // Update the status based on the request
        $booking->status = $request->status;
        $booking->remarks = $request->remarks;
        $booking->save();

        return redirect()->back()->with('success', 'Booking status updated successfully.');
    }
}
