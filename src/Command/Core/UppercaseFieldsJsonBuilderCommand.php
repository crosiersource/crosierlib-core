<?php

namespace CrosierSource\CrosierLibCoreBundle\Command\Core;

use CrosierSource\CrosierLibCoreBundle\Doctrine\Annotations\NotUppercase;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Classe responsável por varrer a estrutura de metadados do Doctrine, encontrar todos os campos que sejam string
 * e montar o uppercaseFields.json.
 * Este arquivo json é utilizado nos eventos de PrePersist e PreUpdate das classes filhas de EntityId para colocar
 * em uppercase todos os campos de caracteres.
 *
 * @author Carlos Eduardo Pauluk
 */
class UppercaseFieldsJsonBuilderCommand extends Command
{

    /** @var EntityManagerInterface */
    private $doctrine;

    /** @var LoggerInterface */
    private $logger;

    /**
     * UppercaseFieldsJsonBuilderCommand constructor.
     * @param EntityManagerInterface $doctrine
     * @param LoggerInterface $logger
     */
    public function __construct(EntityManagerInterface $doctrine, LoggerInterface $logger)
    {
        $this->doctrine = $doctrine;
        $this->logger = $logger;
        parent::__construct();
    }

    /**
     *
     */
    protected function configure()
    {
        $this
            ->setName('crosier:uppercaseFieldsJsonBuilder')
            ->setDescription('Percorre as entidades e cria o u com os campos string para poder utilizar com o UppercaseStrings.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
	{
        try {
            $this->buildJson($output);
            return 1;
        } catch (\Exception $e) {
            $output->writeln('Erro: ' . $e->getMessage());
            $this->logger->debug($e->getMessage());
            return -1;
        }
    }

    public function buildJson(OutputInterface $output)
    {
        $array = [];

        $all = $this->doctrine->getMetadataFactory()->getAllMetadata();

        foreach ($all as $classMeta) {
            $fields = [];
            $eMeta = $this->doctrine->getMetadataFactory()->getMetadataFor($classMeta->getName());
            $this->logger->debug('Pesquisando ' . $classMeta->getName());
            foreach ($eMeta->getFieldNames() as $field) {

				$reflectionClass = new \ReflectionClass($classMeta->getName());

				$possuiNotUppercase = false;

				foreach ($reflectionClass->getProperties() as $propriedade) {
					if ($propriedade->getName() === $field) {
						if ($propriedade->getAttributes(NotUppercase::class)) {
							$possuiNotUppercase = true;
							break;
						}
					}
				}

                if ($possuiNotUppercase) {
                    continue;
                }

                $fieldM = $eMeta->getFieldMapping($field);
                if ($fieldM['type'] == 'string') {
                    $this->logger->debug($field);
                    $fields[] = $field;
                }
            }
            if (count($fields) > 0) {
                $className = str_replace('\\', '_', $classMeta->getName());
                $array[$className] = $fields;
            }
            $this->logger->debug('');
        }

        $normalizer = new ObjectNormalizer();
        $encoder = new JsonEncoder();

        $serializer = new Serializer([$normalizer], [$encoder]);
        $json = $serializer->serialize($array, 'json');

        $this->logger->debug($json);

        file_put_contents('./src/Entity/uppercaseFields.json', $json);

        $output->writeln('Arquivo escrito: ./src/Entity/uppercaseFields.json');

    }

}
