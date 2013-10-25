<?php
namespace FdlGatewayManager;

use Zend\Db;
use Zend\EventManager;

class GatewayFactory extends AbstractServiceLocatorAware
{
    /**
     * @var \Zend\Db\Adapter\Adapter
     */
    protected $adapter;

    /**
     * @var resource
     */
    protected $entity;

    /**
     * @var \FdlGatewayManager\Feature\AbstractFeature
     */
    protected $feature;

    /**
     * @var \FdlGatewayManager\ResultSet\AbstractResultSet
     */
    protected $resultSetPrototype;

    /**
     * Tablename
     * @var string
     */
    protected $table;

    /**
     * @var \Zend\Db\TableGateway\AbstractTableGateway
     */
    protected $tableGateway;

    /**
     * @var string
     */
    protected $tableGatewayProxy;

    /**
     * @var \FdlGatewayManager\GatewayWorker
     */
    protected $gatewayWorker;

    /**
     * Run the factory
     * @param void
     * @return null
     */
    public function run()
    {
        $worker = $this->getWorker();
        $utilities = $this->getServiceLocator()->get('FdlGatewayFactoryUtilities');
        $event = $this->getServiceLocator()->get('FdlGatewayFactoryEvent');
        $eventManager = $this->getEventManager()->setIdentifiers(array(__CLASS__, uniqid()));

        if (isset($worker) && $worker instanceof GatewayWorker) {
            // add the adapter key to the event
            $adapterKeyName = $worker->getAdapterKeyName();
            $event->setAdapterKey($adapterKeyName);

            $entityName     = $worker->getEntityName();
            $resultSetName  = $worker->getResultSetName();
            $featureName    = $worker->getFeatureName();
            $tableName      = $worker->getTableName();
            $tableGatewayName = $worker->getTableGatewayName();

            // load the adapter
            $eventManager->trigger(GatewayFactoryEvent::LOAD_ADAPTER, $this, $event);

            // load the features
            $eventManager->trigger(GatewayFactoryEvent::LOAD_FEATURES, $this, $event);

            // load the result set prototype
            $eventManager->trigger(GatewayFactoryEvent::LOAD_RESULT_SET_PROTOTYPE, $this, $event);

                        die;

                        $table   = $utilities->getTable($tableName, $entity, $adapter);
            $tableGatewayProxy = $utilities->getTableGatewayProxy($tableGatewayName, $entity);


            $resultSet = $utilities->initResultSet($resultSetName);

            $this->setAdapter($adapter);
            $this->setEntity($entity);
            $this->setTable($table);
            $this->setTableGatewayProxy($tableGatewayProxy);


            // initialize resultset
            if (isset($resultSet)) {
                if ($resultSet instanceof ResultSet\ResultSetInterface) {
                    $resultSet->setFdlGatewayFactory($this)->create();
                    $this->setResultSetProtype($resultSet->getResultSet());
                } else {
                    $this->setResultSetProtype($resultSet);
                }
            }

            $worker->assemble($this);
        } else {
            throw new Exception\ClassNotExistException('There is no worker defined');
        }
    }

    /**
     * @return \Zend\Db\TableGateway\AbstractTableGateway
     */
    public function getTableGateway()
    {
        return $this->tableGateway;
    }

    /**
     * @param Db\TableGateway\AbstractTableGateway $tableGateway
     * @return \FdlGatewayManager\GatewayFactory
     */
    public function setTableGateway($tableGateway)
    {
        if (!$tableGateway instanceof Db\TableGateway\AbstractTableGateway
            && !$tableGateway instanceof Gateway\AbstractTable
        ) {
            throw new Exception\InvalidArgumentException('Class needs to be an instance of Gateway\AbstractTable');
        }
        $this->tableGateway = $tableGateway;
        return $this;
    }

    /**
     * @return string
     */
    public function getTableGatewayProxy()
    {
        return $this->tableGatewayProxy;
    }

    /**
     * @param string $tableGatewayString
     */
    public function setTableGatewayProxy($tableGatewayProxy)
    {
        $this->tableGatewayProxy = $tableGatewayProxy;
        return $this;
    }

    /**
     * Returns the gateway worker
     * @return \FdlGatewayManager\GatewayWorker
     */
    public function getWorker()
    {
        return $this->gatewayWorker;
    }

    /**
     * @param GatewayWorker $worker
     * @return \FdlGatewayManager\GatewayFactory
     */
    public function setWorker(GatewayWorker $worker = null)
    {
        $this->gatewayWorker = $worker;
        return $this;
    }

    /**
     * @return \Zend\Db\Adapter\Adapter
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * @param \Zend\Db\Adapter\Adapter $adapter
     * @return \FdlGatewayManager\GatewayFactory
     */
    public function setAdapter(Db\Adapter\Adapter $adapter)
    {
        $this->adapter = $adapter;
        return $this;
    }

    /**
     * @param void
     * @return Object Entity object
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Set the entity object
     * @param object $entity
     * @return \FdlGatewayManager\GatewayFactory
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * @return \FdlGatewayManager\Feature\AbstractFeature
     */
    public function getFeature()
    {
        return $this->feature;
    }

    /**
     * @param Db\TableGateway\Feature\AbstractFeature $feature
     * @return \FdlGatewayManager\GatewayFactory
     */
    public function setFeature(Db\TableGateway\Feature\AbstractFeature $feature)
    {
        $this->feature = $feature;
        return $this;
    }

    /**
     * @return \FdlGatewayManager\ResultSet\AbstractResultSet
     */
    public function getResultSetPrototype()
    {
        return $this->resultSetPrototype;
    }

    /**
     * @param Db\ResultSet\ResultSetInterface $resultSetPrototype
     */
    public function setResultSetPrototype(Db\ResultSet\ResultSetInterface $resultSetPrototype)
    {
        $this->resultSetPrototype = $resultSetPrototype;
    }

    /**
     * @return object table object
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @param object $table
     */
    public function setTable($table)
    {
        $this->table = $table;
    }

    /**
     * Reset the gateway worker
     * @param void
     */
    public function reset()
    {
        $properties = get_object_vars($this);
        while (list($key) = each($properties)) {
            if ($key != 'serviceLocator') {
                $this->{$key} = null;
            }
        }
    }
}
