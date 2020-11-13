<?php

use Brain\DataBase\ {
    QueryBuulder
};
use Brain\Session\Session;

return [
    
    Session::class => DI\create(Session::class),

    QueryBuulder::class => DI\create(QueryBuulder::class)
                            ->constructor(DI\get(DBBuilder::class))

];