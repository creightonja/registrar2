<?php

require_once = "src/Student.php";
require_once = "src/Course.php";

class Completion {

    private $completion;
    private $student_id;
    private $course_id;
    private $id;


//Incomplete function for marking complete status in join table.
    function __construct($completion, $student_id, $course_id, $id = null) {
        $this->completion = $completion;
        $this->student_id = $student_id;
        $this->course_id = $course_id;
        $this->id = $id;
    }

    function setCompletion($new_completion) {
        $this->completion = (string) $new_completion;
    }

    function getDepartmentName() {
        return $this->department_name;
    }

    function getStudentId() {
        return $this->student_id;
    }

    function getCourseId() {
        return $this->course_id;
    }

    function getId(){
        return $this->id;
    }

    function save(){
        $GLOBALS['DB']->exec("INSERT INTO departments (completion, student_id, course_id)
                    VALUES ({$this->getCompletion()}, {$this->getStudentId()}, {$this->getCourseId()});");
        $this->id = $GLOBALS['DB']->lastInsertId();
    }

    function getStudents() {
        $returned_students = $GLOBALS['DB']->query("SELECT * FROM students;");
        $students = array();
        foreach ($returned_students as $student ) {
            $student_name = $student['student_name'];
            $id = $student['id'];
            $enrollment_date = $student['enrollment_date'];
            $new_student = new Student($student_name, $id, $enrollment_date);
            array_push($students, $new_student);
        }
        return $students;
    }

    function getCourses() {
        $returned_courses = $GLOBALS['DB']->query("SELECT * FROM courses;");
        $courses = array();
        foreach ($returned_courses as $course) {
            $course_name = $course['course_name'];
            $id = $course['id'];
            $course_number = $course['course_number'];
            $new_course = new Course($course_name, $id, $course_number);
            array_push($courses, $new_course);
        }
        return $courses;
    }
}

?>
