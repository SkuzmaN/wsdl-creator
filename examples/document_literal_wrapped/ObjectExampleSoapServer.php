<?php
use WSDL\DocumentLiteralWrapper;
use WSDL\WSDLCreator;
use WSDL\XML\Styles\DocumentLiteralWrapped;

require_once '../../vendor/autoload.php';

$wsdl = new WSDLCreator('ObjectSoapServer', 'http://localhost/wsdl-creator/examples/document_literal_wrapped/ObjectExampleSoapServer.php');
$wsdl->setNamespace("http://foo.bar/")->setBindingStyle(new DocumentLiteralWrapped());

if (isset($_GET['wsdl'])) {
    $wsdl->renderWSDL();
    exit;
}

$wsdl->renderWSDLService();

$server = new SoapServer('http://localhost/wsdl-creator/examples/document_literal_wrapped/ObjectExampleSoapServer.php?wsdl', array(
    'uri' => $wsdl->getNamespaceWithSanitizedClass(),
    'location' => $wsdl->getLocation(),
    'style' => SOAP_DOCUMENT,
    'use' => SOAP_LITERAL
));
$server->setObject(new DocumentLiteralWrapper(new ObjectSoapServer()));
$server->handle();

class Agent
{
    /**
     * @type string
     */
    public $name;
    /**
     * @type int
     */
    public $number;
}

class ObjectSoapServer
{
    /**
     * @param object $info @string=$name @int=$age
     * @return string $returnInfo
     */
    public function userInfo($info)
    {
        return 'Your name is: ' . $info->name . ' and you have ' . $info->age . ' years old, it\'s ok?';
    }

    /**
     * @param string $name
     * @param string $number
     * @return object $agentNameWithId @(wrapper $agent @className=Agent) @int=$id
     */
    public function getAgentWithId($name, $number)
    {
        $agent = new Agent();
        $agent->name = $name;
        $agent->number = $number;

        $return = new stdClass();
        $return->agent = $agent;
        $return->id = 3543456;
        return $return;
    }

    /**
     * @param object $namesInfo @string[]=$names @int=$id
     * @return string $namesForId
     */
    public function namesForId($namesInfo)
    {
        //FIXME incorrect $names array
        return '[#' . $namesInfo->id . '] Names: ' . implode(', ', $namesInfo->names);
    }

    /**
     * @return object[] $companies @string=$name @int=$id
     */
    public function getCompanies()
    {
        //FIXME incorrect response structure
        $companies = array();
        $companies[0] = new stdClass();
        $companies[0]->name = 'Example1';
        $companies[0]->id = '1';
        $companies[1] = new stdClass();
        $companies[1]->name = 'Example2';
        $companies[1]->id = '3';
        return $companies;
    }

    /**
     * @return object $listOfAgents @(wrapper[] $agents @className=Agent) @int=$id
     */
    public function getListOfAgentsWithId()
    {
        //FIXME incorrect response structure
        $obj = new stdClass();
        $obj->agents[0] = new Agent();
        $obj->agents[0]->name = 'agent1';
        $obj->agents[1] = new Agent();
        $obj->agents[1]->name = 'agent2';
        $obj->id = '555';
        return $obj;
    }

    /**
     * @param object[] $payments @float[]=$payment @string=$user
     * @return object[] $paymentsUsers @string=$user @int=$countPayment
     */
    public function setPayment($payments)
    {
        //FIXME incorrect response structure
        $paymentsUsers = array();
        foreach ($payments as $i => $payment) {
            $paymentsUsers[$i] = new stdClass();
            $paymentsUsers[$i]->user = $payment->user;
            $paymentsUsers[$i]->countPayment = count($payment->payment);
        }
        return $paymentsUsers;
    }

    /**
     * @return object[] $agentsWithPayment @(wrapper $agent @className=Agent) @float=$payment
     */
    public function getAgentsWithPayment()
    {
        //FIXME incorrect response structure
        $obj = array();
        $obj[0] = new stdClass();
        $obj[0]->agent = new Agent();
        $obj[0]->agent->name = 'agent1';
        $obj[0]->payment = '123.56';
        $obj[1] = new stdClass();
        $obj[1]->agent = new Agent();
        $obj[1]->agent->name = 'agent2';
        $obj[1]->payment = '6546.56';
        return $obj;
    }

    /**
     * @return object[] $employeesList @(wrapper[] $agents @className=Agent)
     */
    public function getEmployeesWithAgents()
    {
        //FIXME incorrect response structure
        $obj = array();
        $obj[0] = new stdClass();
        $obj[0]->agents[0] = new Agent();
        $obj[0]->agents[0]->name = 'agent1';
        $obj[0]->agents[1] = new Agent();
        $obj[0]->agents[1]->name = 'agent2';
        $obj[1] = new stdClass();
        $obj[1]->agents[0] = new Agent();
        $obj[1]->agents[0]->name = 'agent3';
        $obj[1]->agents[1] = new Agent();
        $obj[1]->agents[1]->name = 'agent4';
        return $obj;
    }
}