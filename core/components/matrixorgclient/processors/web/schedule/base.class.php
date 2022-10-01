<?php

/**
 * Class mocBaseProcessor
 */
abstract class mocBaseProcessor extends modProcessor {

    public function initialize() {

        $modx = &$this->modx;

        $mocCorePath = realpath($modx->getOption('matrixorgclient.core_path', null, $modx->getOption('core_path') . 'components/matrixorgclient')) . '/';
        $this->moc = $modx->getService('matrixorgclient', 'MatrixOrgClient', $mocCorePath . 'model/matrixorgclient/');

        $fbuchCorePath = realpath($modx->getOption('fbuch.core_path', null, $modx->getOption('core_path') . 'components/fbuch')) . '/';
        $this->fbuch = $modx->getService('fbuch', 'Fbuch', $fbuchCorePath . 'model/fbuch/');        

        $date_id = $this->getProperty('date_id');
        $this->date_object = $modx->getObject('fbuchDate', array('id' => $date_id));

        $name_id = $this->getProperty('name_id');
        $this->name_object = $modx->getObject('mvMember', array('id' => $name_id));

        $this->run = $this->getProperty('run');
        $this->task = $this->getProperty('task');

        //echo '<pre>' . print_r($date_object->toArray(), 1) . '</pre>';
        //die();

        
        

        return true;
    }
    
    public function reschedule(){
        $reschedule_times = array('','+5 minute','+1 hour','+6 hour','+12 hour','+24 hour');
        $data = $this->run->get('data');
        $count = isset($data['reschedule_count']) ? $data['reschedule_count'] +1 : 1;
        $timing = isset($reschedule_times[$count]) ? $reschedule_times[$count] : 'canceled';
        
        $data['reschedule_count'] = $count;
        
        if ($timing != 'canceled'){
            $this->task->schedule($timing,$data);     
        }
        
        return $timing;       
                
    }


}

