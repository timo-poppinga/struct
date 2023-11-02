<?php

declare(strict_types=1);

namespace Struct\Struct\Utility;

use Struct\Contracts\StructCollectionInterface;
use Struct\Contracts\StructInterface;
use Struct\Struct\Private\Utility\HashUtility;

/**
 * @deprecated
 */
class StructHashUtility
{
    protected HashUtility $hashUtility;

    public function __construct()
    {
        $this->hashUtility = new HashUtility();
    }

    /**
     * @param StructInterface|StructCollectionInterface $structure
     */
    public function buildHash(StructInterface|StructCollectionInterface $structure): string
    {
        $hashString = $this->hashUtility->buildHashString($structure);
        return \hash('sha512', $hashString, true);
    }
}
