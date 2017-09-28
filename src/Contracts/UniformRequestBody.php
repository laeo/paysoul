<?php

namespace Paysoul\Contracts;

interface UniformRequestBody
{
    public function __construct(Transaction $trans);

    /**
     * 附加键值对到请求体中
     *
     * @param  string $key
     * @param  string $value
     * @return void
     */
    public function append(string $key, string $value);

    public function map(array $pairs);

    public function toArray(): array;

    public function toString();
}
