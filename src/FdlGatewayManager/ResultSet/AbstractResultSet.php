<?php
namespace FdlGatewayManager\ResultSet;

use FdlGatewayManager\GatewayFactory;

abstract class AbstractResultSet implements ResultSetInterface
{
    /**
     * @var GatewayFactory
     */
    protected $gatewayFactory;

    /**
     * @var \Zend\Db\ResultSet\ResultSetInterface
     */
    protected $resultSetPrototype;

    /**
     * (non-PHPdoc)
     * @see \FdlGatewayManager\ResultSet\ResultSetInterface::getResultSetPrototype()
     */
    public function getResultSetPrototype()
    {
        return $this->resultSetPrototype;
    }

    /**
     * @return GatewayFactory;
     */
    public function getGatewayFactory()
    {
        return $this->gatewayFactory;
    }

    /**
     * @param GatewayFactory $factory;
     */
    public function setGatewayFactory(GatewayFactory $factory)
    {
        $this->gatewayFactory = $factory;
        return $this;
    }
}
