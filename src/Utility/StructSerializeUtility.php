<?php

declare(strict_types=1);

namespace Struct\Struct\Utility;

use Struct\Struct\Contracts\StructInterface;
use Struct\Struct\Exception\UnexpectedException;
use Struct\Struct\Private\Utility\SerializeUtility;
use Struct\Struct\Private\Utility\UnSerializeUtility;

class StructSerializeUtility
{
    protected SerializeUtility $serializeUtility;
    protected UnSerializeUtility $unSerializeUtility;

    public function __construct()
    {
        $this->serializeUtility = new SerializeUtility();
        $this->unSerializeUtility = new UnSerializeUtility();
    }

    /**
     * @return mixed[]
     */
    public function serialize(StructInterface $structure): array
    {
        return $this->serializeUtility->serialize($structure);
    }

    public function serializeJson(StructInterface $structure): string
    {
        $dataArray = $this->serialize($structure);
        $dataJson = \json_encode($dataArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if ($dataJson === false) {
            throw new UnexpectedException(1675972511);
        }
        return $dataJson;
    }

    /**
     * @param mixed[]|object $data
     * @param class-string<StructInterface> $type
     */
    public function unSerialize(array|object $data, string $type): StructInterface
    {
        return $this->unSerializeUtility->unSerialize($data, $type);
    }

    /**
     * @param class-string<StructInterface> $type
     */
    public function unSerializeJson(string $dataJson, string $type): StructInterface
    {
        try {
            /** @var mixed[] $dataArray */
            $dataArray = \json_decode($dataJson, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            throw new \LogicException('Can not parse the given JSON string', 1675972764, $exception);
        }
        return $this->unSerializeUtility->unSerialize($dataArray, $type);
    }
}
