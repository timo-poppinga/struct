<?php

declare(strict_types=1);

namespace Struct\Struct\Utility;

use Struct\Contracts\StructInterface;
use Struct\Struct\Private\Utility\HashUtility;

class StructHashUtility
{
    protected HashUtility $hashUtility;

    public function __construct()
    {
        $this->hashUtility = new HashUtility();
    }

    public function buildHash(StructInterface $structure): string
    {
        $hashString = $this->hashUtility->buildHashString($structure);
        return \hash('sha512', $hashString, true);
    }
}
