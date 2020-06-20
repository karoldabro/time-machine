<?php

return [
    'format' => 'Y-m-d H:i:s',
    'filed-types' => [
        'date' => \Kdabrow\TimeMachine\Database\FieldTypes\DateFieldType::class,
        'datetime' => \Kdabrow\TimeMachine\Database\FieldTypes\DateTimeFieldType::class,
        'timestamp' => \Kdabrow\TimeMachine\Database\FieldTypes\TimestampFieldType::class,
    ]
];
