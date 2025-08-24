<?php

namespace App\Livewire\User;

use App\Models\Application;
use Livewire\Component;
use Livewire\Attributes\Layout;

class Gesuch extends Component
{
    #[Layout('components.layout.user-dashboard')]

    public function deleteApplication($applicationId)
    {
        try {
            $application = Application::LoggedInUser()->findOrFail($applicationId);

            // Allow deletion of draft applications
            if ($application->appl_status->value === 'Not Send') {
                $application->delete();
                session()->flash('message', __('application.application_deleted_successfully'));
            }
            // Allow deletion of submitted applications that are not approved/finished
            elseif (in_array($application->appl_status->value, ['Pending', 'Waiting', 'Complete', 'Blocked'])) {
                $application->delete();
                session()->flash('message', __('application.application_deleted_successfully'));
            }
            // Don't allow deletion of approved or finished applications
            else {
                session()->flash('error', __('application.cannot_delete_approved_application'));
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            session()->flash('error', __('application.application_not_found'));
        } catch (\Exception $e) {
            session()->flash('error', __('application.error_deleting_application'));
        }
    }

    public function render()
    {
        $applications = Application::LoggedInUser()
            ->where('appl_status', '!=', 'Not Send')
            ->get();

        return view('livewire.user.gesuch', [
            'applications' => $applications,
        ]);
    }
}
