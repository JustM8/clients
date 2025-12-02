<?php

return [

    // INDEX
    'index' => [
        'title' => 'Projects',
        'add_button' => 'Add project',

        'table' => [
            'id' => 'ID',
            'name' => 'Name',
            'client' => 'Client',
            'status' => 'Status',
            'created_at' => 'Created',
            'actions' => 'Actions',
            'edit' => 'Edit',
            'delete_confirm' => 'Delete project?',
            'deleted' => 'Deleted',
        ],
    ],


    // EDIT / SHOW
    'edit' => [
        'title' => 'Project: :name',

        'fields' => [
            'name' => 'Name',
            'description' => 'Description',
            'type' => 'Project type',
            'client' => 'Client',
            'status' => 'Stage',
            'rate' => 'Rate (€/hour)',
            'buffer_hours' => 'Buffer time before waiting (hours)',
        ],

        'timer_card' => [
            'title' => 'Stage Timer',
            'start' => 'Start',
            'stop' => 'Stop',
            'elapsed' => 'Elapsed',
            'select_stage' => 'Select stage',
        ],

        'stages' => [
            'title' => 'Project stages',

            'table' => [
                'name' => 'Stage',
                'start' => 'Start',
                'end' => 'End',
                'expected' => 'Expected date',
                'spent' => 'Spent',
                'actions' => 'Actions',
                'delete' => 'Delete',
            ],

            'add_button' => 'Add stage',
        ],

        'buttons' => [
            'save' => 'Update',
            'back' => 'Back',
        ],
    ],


    // WAITING CARD
    'waiting' => [

        'title' => 'Client waiting',

        'start' => [
            'label' => 'What are we waiting for?',
            'placeholder' => 'Describe what we are waiting for from the client',
            'button' => 'Start waiting',
        ],

        'running' => [
            'title' => 'Active waiting: client has not provided information yet.',
            'timer' => 'Timer',
            'started_at' => 'Started',
            'comment' => 'Waiting for:',
        ],

        'pending' => [
            'title' => 'Client responded. Waiting for manager approval.',
            'comment' => 'Client comment:',
            'started_at' => 'Started',
        ],

        'rejected' => [
            'title' => 'Manager rejected the response. Client must respond again.',
            'reason' => 'Rejection reason:',
            'started_at' => 'Started',
        ],

        'history_title' => 'Waiting history',

        'history_status' => [
            'running' => 'Waiting started',
            'pending' => 'Client responded (pending)',
            'rejected' => 'Response rejected',
            'completed' => 'Completed',
        ],

        'messages' => [
            'client' => 'Client responded',
            'admin' => 'Team responded',
            'empty' => 'No history yet.',
        ],

    ],


    // CHAT SECTION
    'chat' => [
        'title' => 'Chat with manager',

        'message' => [
            'client' => 'Client',
            'admin'  => 'Admin',
        ],

        'empty' => 'No messages yet.',

        'form' => [
            'placeholder' => 'Write a message...',
            'send' => 'Send',
        ],
    ],


    // CREATE FORM
    'create' => [
        'title' => 'Create project',

        'fields' => [
            'name' => 'Name',
            'description' => 'Description',
            'type' => 'Project type',
            'client' => 'Client',
            'status' => 'Status',
            'rate' => 'Rate (€/hour)',
        ],

        'buttons' => [
            'save' => 'Save',
            'back' => 'Back',
        ],
    ],
    'stages' => [
        'Збір інформації'       => 'Information gathering',
        'Обробка інформації'    => 'Information processing',
        'Виробництво'           => 'Production',
        'Прийняття роботи'      => 'Work acceptance',
        'Оплата'                => 'Payment',
    ],
    'js' => [
        'select_stage' => 'Select stage',
        'enter_comment' => 'Enter a comment',
    ],


];
