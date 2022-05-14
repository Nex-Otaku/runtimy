<?php

namespace App\Module\Customer;

interface SerializableItem
{
    public function serialize(): array;
}
