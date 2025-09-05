<?php

use App\Enums\ApplStatus;
use App\Enums\Bereich;
use App\Enums\Form;
use App\Enums\PayoutPlan;
use App\Enums\Time;

return [
    'name' => 'Name of the application',
    'application' => 'Application',
    'applications' => 'Applications',
    'newApplication' => 'Create new application',
    'createdAt' => 'Created at',
    'updatedAt' => 'Last updated at',
    'area' => 'Area',
    'form' => 'Form',
    'desiredForm' => 'Desired form of the application',
    'desiredCurrency' => 'Desired currency',
    'startDate' => 'Start Date',
    'endDate' => 'End Date',
    'firstAppl' => 'First Application',
    'followAppl' => 'Follow Up Application',
    'currency' => 'Curreny',
    'calc_amount' => 'Calculated Amount',
    'req_amount' => 'Required Amount',
    'payout_plan' => 'Payout Plan',
    'start_appl' => 'Start of the application',
    'submission_date' => 'Submitted on',
    'end_appl' => 'End of the application',
    'no_applications' => 'No application found',
    'appl_overview' => 'Applications Overview',
    'appl_overview_text' => 'Status Pending, Waiting for the user and Waiting for the foundation meeting',
    'proj_overview' => 'Project Overview',
    'proj_overview_text' => 'Ongoing projects (approved applications)',
    'no_projects' => 'No projects found',
    'bereich' => 'Area',
    'requests' => 'Requests',
    'no_requests' => 'No requests found',
    'status' => 'State',
    'is_first' => 'First Application',
    'currency_id' => 'Currency',
    'proj_blocked' => 'Blocked Applications',
    'proj_blocked_text' => 'Applications with status "blocked"',
    'proj_notsend' => 'Not Send Applications',
    'proj_notsend_text' => 'Application with status "not send"',

    // New translations for application type display
    'application_type' => 'Application Type',
    'first_application' => 'First Application',
    'follow_up_application' => 'Follow-up Application',
    'main_application' => 'Main Application',
    'main_application_not_found' => 'Main application not found',

    // Application deletion messages
    'application_deleted_successfully' => 'Application deleted successfully',
    'cannot_delete_submitted_application' => 'Submitted applications cannot be deleted',
    'cannot_delete_approved_application' => 'Approved or finished applications cannot be deleted',
    'confirm_delete_application' => 'Are you sure you want to delete this application?',
    'application_not_found' => 'Application not found',
    'error_deleting_application' => 'Error deleting application',
    'past_applications' => 'Past Applications',

    'status_name' => [
        ApplStatus::NOTSEND->name => 'Not send',
        ApplStatus::PENDING->name => 'Pending',
        ApplStatus::WAITING->name => 'Waiting for the user',
        ApplStatus::COMPLETE->name => 'Waiting for the foundation meeting',
        ApplStatus::APPROVED->name => 'Approved',
        ApplStatus::BLOCKED->name => 'Declined',
        ApplStatus::FINISHED->name => 'Finished',
    ],

    'bereichs_name' => [
        Bereich::Bildung->name => 'Education',
        Bereich::Menschen->name => 'People in need',
        Bereich::Menschenrecht->name => 'Human rights',
        Bereich::Tierschutz->name => 'Animal welfare',
        Bereich::Umwelt->name => 'Environment',
    ],

    'form_name' => [
        Form::Stipendium->name => 'Scholarship',
        Form::Darlehen->name => 'Loan',
        Form::Spende->name => 'Donation',
    ],

    'payoutplan_name' => [
        PayoutPlan::monthly->name => 'monthly',
        PayoutPlan::oneTime->name => 'one time',
        PayoutPlan::partialAmount->name => 'Can be requested in individual installments',
    ],

    'time' => [
        Time::Teilzeit->name => 'Part Time',
        Time::Vollzeit->name => 'Full Time',
    ],

    // Application editing restrictions
    'edit_restriction_warning' => 'This application has already been approved and can no longer be edited.',
    'edit_restriction_hint' => 'Notice:',
    'edit_restriction_error' => 'This application cannot be edited because it has already been approved.',
];
