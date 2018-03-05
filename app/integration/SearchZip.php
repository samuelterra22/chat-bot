<?php

namespace ChatBot\integration;


class SearchZip
{

    protected $zip;

    public function __construct(string $zip)
    {
        $this->zip = $zip;
    }

    /**
     * @TODO: implement address search using viacep
     */
    public function handle()
    {
        return [
            "cep"         => "01001-000",
            "logradouro"  => "Praça da Sé",
            "complemento" => "lado ímpar",
            "bairro"      => "Sé",
            "localidade"  => "São Paulo",
            "uf"          => "SP",
            "unidade"     => "",
            "ibge"        => "3550308",
            "gia"         => "1004"
        ];
    }

}