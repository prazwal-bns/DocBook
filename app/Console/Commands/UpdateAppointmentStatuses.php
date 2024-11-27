<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use Illuminate\Console\Command;
// use Carbon\Carbon;

class UpdateAppointmentStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-appointment-statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically update statuses of appointments if the date has passed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        /* For testing -> use below code
        $now = Carbon::now();

        // Testing logic
        $updatedAppointments = Appointment::where('status', '!=', 'completed')
            ->whereTime('updated_at', '<', $now->format('H:i')) // Compare time (hour:minute)
            ->update(['status' => 'completed']);
            */

        $updatedAppointments = Appointment::where('status', '!=', 'completed')
            ->where('appointment_date', '<', now())
            ->update(['status' => 'completed']);


        $this->info("$updatedAppointments appointments were updated to 'completed'.");
    }
}
