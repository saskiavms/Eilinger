<?php
use \App\Enums\Education;
use App\Enums\Grade;

return [
    'education' => 'Education',
    'subtitle' => 'for which contributions are required',
    'name' => 'Name and location of the education center',
    'final' => 'Intended degree as',
    'grade' => 'Grade',
    'ects_points' => 'ECTS points for the coming semester according to receipt',
    'time' => 'Workload',
    'begin_edu' => 'Begin of the education',
    'duration_edu' => 'Duration of the education',
    'start_semester' => 'Payment request from training year/semester',

    'education_name' => [
        Education::Matura->name => 'High school diploma',
        Education::FMS->name => 'FMS',
        Education::Berufslehre->name => 'Apprenticeship',
        Education::BM2->name => 'BM2',
        Education::Fachschule->name => 'Technical school',
        Education::Fachhochschule->name => 'Other further education',
        Education::Universitaet->name => 'University',
    ],

    'grade_name' => [
        Grade::Highschool->name => 'Highschool',
        Grade::Other->name => 'Other',
    ],
];

