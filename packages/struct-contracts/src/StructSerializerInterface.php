<?php

declare(strict_types=1);

namespace Struct\Struct\Contracts;

interface StructSerializerInterface
{
    /**
     * @param StructInterface $structure
     * @return mixed[]
     */
    public function serialize(StructInterface $structure): array;

    /**
     * @param mixed[] $data
     * @param class-string<StructInterface> $type
     * @return StructInterface
     */
    public function unSerialize(array $data, string $type): StructInterface;

    /**
     * @param StructInterface $structure
     * @return string
     */
    public function serializeJson(StructInterface $structure): string;

    /**
     * @param string $dataJson
     * @param class-string<StructInterface> $type
     * @return StructInterface
     */
    public function unSerializeJson(string $dataJson, string $type): StructInterface;
}
