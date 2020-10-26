<?php

return [

    "connections" => [
        "default" => [
            "driver" => \getenv("DB_DRIVER"),
            "host" => \getenv("DB_HOST"),
            "database" => \str_replace("%APP_ROOT%", APP_ROOT, \getenv("DB_DATABASE")),
            "username" => \getenv("DB_USER"),
            "password" => \getenv("DB_PASSWORD"),
            "date_format" => \getenv("DB_DATEFORMAT")
        ]
    ]
];