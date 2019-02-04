<?php

namespace Ivoz\Core\Application;

interface DataTransferObjectInterface
{
    const CONTEXT_COLLECTION = 'collection';
    const CONTEXT_SIMPLE = '';
    const CONTEXT_DETAILED = 'detailed';

    const CONTEXT_TYPES = [
        self::CONTEXT_COLLECTION,
        self::CONTEXT_SIMPLE,
        self::CONTEXT_DETAILED
    ];

    public function getId();

    /**
     * @return array
     */
    public function normalize(string $context);

    /**
     * @return void
     */
    public function denormalize(array $data, string $context);

    /**
     * @return array
     */
    public static function getPropertyMap(string $context = '');

    /**
     * @return array
     */
    public function toArray();
}
