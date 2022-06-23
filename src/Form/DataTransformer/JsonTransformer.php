<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * Handles transforming json to array and backward
 */
class JsonTransformer implements DataTransformerInterface
{

    /**
     * @inheritDoc
     */
    public function reverseTransform($value)
    {
        if (empty($value)) {
            return [];
        }

//        dump($value);
//        dump(json_decode($value));
//        die(__FILE__ . __LINE__);

        return json_decode($value, true);
    }

    /**
     * @ihneritdoc
     */
    public function transform($value)
    {
        if (empty($value)) {
            return json_encode([]);
        }

        return json_encode($value);
    }
}