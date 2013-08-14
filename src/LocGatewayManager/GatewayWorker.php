<?php
namespace LocGatewayManager;

use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager;

class GatewayWorker implements WorkerInterface, ServiceManager\ServiceLocatorAwareInterface
{
    /**
     * @var string
     */
    protected $adapterKeyName;

    /**
     * @var string
     */
    protected $entityName;

    /**
     * @var string
     */
    protected $featureName;

    /**
     * @var string
     */
    protected $resultSetName;

    /**
     * @var string
     */
    protected $tableName;

    /**
     * @var TableGateway
     */
    protected $tableGateway;

    /**
     * @var ServiceManager\ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * The worker assembles the table gateway
     * @param string $entityName Table Entity
     * @return TableGateway;
     */
    public function assemble(GatewayFactory $factory)
    {
        $tableGateway = $this->getServiceLocator()->get('LocGatewayTableGateway');
        $factory->setTableGateway($tableGateway);
    }

    /**
     * Retrieve the adapter key name
     * @param void
     * @return string
     */
    public function getAdapterKeyName()
    {
        return $this->adapterKeyName;
    }

    /**
     * Set the adapter key name
     * Note: the key is array key on config.
     * @param string $adapterKey
     * @return \LocDb\LocDbManager
     */
    public function setAdapterKeyName($adapterKey)
    {
        $this->adapterKeyName = $adapterKey;
        return $this;
    }

    /**
     * Return the table name
     * @return string
     */
    public function getEntityName()
    {
        return $this->entityName;
    }

    /**
     * @param string $entityName Table name
     * @return LocDbManager
     */
    public function setEntityName($entityName)
    {
        $this->entityName = $entityName;
        return $this;
    }

    /**
     * Get the feature name
     * @param void
     * @return string
     */
    public function getFeatureName()
    {
        return $this->featureName;
    }

    /**
     * Set the feature name to use
     * @param string $feature
     * @return \LocDb\LocDbManager
     */
    public function setFeatureName($feature)
    {
        $this->featureName = $feature;
        return $this;
    }

    /**
     * Result set name
     * @param void
     * @return string
     */
    public function getResultSetName()
    {
        return $this->resultSetName;
    }

    /**
     * @param string $resultSet
     * @return \LocDb\LocDbManager
     */
    public function setResultSetName($resultSet)
    {
        $this->resultSetName = $resultSet;
        return $this;
    }

    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * @param string $table
     */
    public function setTableName($table)
    {
        $this->tableName = $table;
    }

    /**
     * Reset the gateway worker
     * @param void
     */
    public function reset()
    {
        $properties = get_object_vars($this);
        while(list($key) = each($properties)) {
            $this->{$key} = null;
        }
    }

    /**
     * Get service locator
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        if (null === $this->serviceLocator) {
            throw new Exception\ClassNotExistException('Service Locator is not set');
        }
        return $this->serviceLocator;
    }

    /**
     * Set service locator
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }
}
