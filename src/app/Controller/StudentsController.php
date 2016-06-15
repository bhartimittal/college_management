<?php
App::uses('AppController', 'Controller');
/**
 * Students Controller
 *
 * @property Student $Student
 * @property PaginatorComponent $Paginator
 */
class StudentsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$query = isset($this->request->params['query'])? $this->request->params['query'] :'';
		
		if(empty($query)){
		$this->Student->recursive = 0;
		$this->set('students', $this->Paginator->paginate());
		}else{
			if(is_array($query) && !empty($query)){	
			  $this->Student->recursive = 0;
			  $this->set('students', $this->Student->find('all', $query));
			}
			
		}
	}

/**
 * search method
 *
 * @return void
 */
	public function search($options=array()) {
		if ($this->request->is('post')) {
		    $conditions =array();
			$name = $this->request->data['student']['name'];
			if(!empty($name)){
				$conditions[] = array(array('Student.name LIKE'=>'%'.$name.'%'));
			}
			$course_id = $this->request->data['student']['course_id'];
			if(!empty($course_id)){
				$conditions[] = array(array('Student.course_id'=>$course_id));
			}
			$email = $this->request->data['student']['email'];
			if(!empty($email)){
				$conditions[] = array(array('Student.email LIKE'=>'%'.$email.'%'));
			}
			$mobile =$this->request->data['student']['mobile'];
			if(!empty($mobile)){
				$conditions[] = array(array('Student.mobile LIKE'=>'%'.$mobile.'%'));
			}
			$status =$this->request->data['student']['status'];
			if(!empty($status)){
				$conditions[] = array(array('Student.status'=>$status));
			}
			if(!empty($conditions)){
				//$options = array('conditions' => $conditions);
				//$students = $this->requestAction('Students/index',array('return','query'=> $options));
				//$this->Student->recursive = 0;
				$this->paginate = array(
					'conditions' => $conditions,
					'limit' => 20,
				);
			}	
			    //$this->set('students', $this->Student->find('all', $options),$this->Paginator->paginate());
				$this->set('students', $this->paginate($this->Student));
			
		}else{
		$courses = $this->Student->Course->find('list');
		$this->set(compact('courses'));
		}
	}	

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Student->exists($id)) {
			throw new NotFoundException(__('Invalid student'));
		}
		$options = array('conditions' => array('Student.' . $this->Student->primaryKey => $id));
		$this->set('student', $this->Student->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Student->create();
			if ($this->Student->save($this->request->data)) {
				$this->Session->setFlash(__('The student has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The student could not be saved. Please, try again.'));
			}
		}
		$courses = $this->Student->Course->find('list');
		$this->set(compact('courses'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Student->exists($id)) {
			throw new NotFoundException(__('Invalid student'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Student->save($this->request->data)) {
				$this->Session->setFlash(__('The student has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The student could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Student.' . $this->Student->primaryKey => $id));
			$this->request->data = $this->Student->find('first', $options);
		}
		$courses = $this->Student->Course->find('list');
		$this->set(compact('courses'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Student->id = $id;
		if (!$this->Student->exists()) {
			throw new NotFoundException(__('Invalid student'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Student->delete()) {
			$this->Session->setFlash(__('The student has been deleted.'));
		} else {
			$this->Session->setFlash(__('The student could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
