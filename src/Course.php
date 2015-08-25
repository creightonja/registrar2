<?php

    require_once "Student.php";

    class Course {
        private $course_name;
        private $id;
        private $course_number;

        function __construct($course_name, $id = null, $course_number) {
            $this->course_name = $course_name;
            $this->id = $id;
            $this->course_number = $course_number;
        }

        function setCourseName($new_course_name) {
            $this->course_name = (string) $new_course_name;
        }

        function getCourseName() {
            return $this->course_name;
        }

        function getId() {
            return $this->id;
        }

        function getCourseNumber() {
            return $this->course_number;
        }

        function save() {
            $GLOBALS['DB']->exec("INSERT INTO courses (course_name, course_number) VALUES ('{$this->getCourseName()}', '{$this->getCourseNumber()}')");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        function getStudents() {
            $query = $GLOBALS['DB']->query("SELECT student_id FROM classes_taken WHERE course_id = {$this->getId()};");
            $student_ids = $query->fetchAll(PDO::FETCH_ASSOC);
            $students = Array();
            foreach($student_ids as $id) {
                $student_id = $id['student_id'];
                $result = $GLOBALS['DB']->query("SELECT * FROM students WHERE id = {$student_id};");
                $returned_student = $result->fetchAll(PDO::FETCH_ASSOC);
                $student_name = $returned_student[0]['student_name'];
                $id = $returned_student[0]['id'];
                $enrollment_date = $returned_student[0]['enrollment_date'];
                $new_student = new Student($student_name, $id, $enrollment_date);
                array_push($students, $new_student);
            }
            return $students;
        }

        function update($new_course_name) {
            $GLOBALS['DB']->exec("UPDATE courses set course_name = '{$new_course_name}' WHERE id = {$this->getId()};");
            $this->setCourseName($new_course_name);
        }

        function deleteOne()
        {
            $GLOBALS['DB']->exec("DELETE FROM courses WHERE id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM classes_taken WHERE course_id = {$this->getId()};");
        }

        function addStudent($student) {
            $GLOBALS['DB']->exec("INSERT INTO classes_taken (course_id, student_id) VALUES ({$this->getId()}, {$student->getId()});");
        }

        static function getAll() {
            $returned_courses = $GLOBALS['DB']->query("SELECT * FROM courses ORDER BY course_number;");
            $courses = array();
            foreach($returned_courses as $course) {
                $course_name = $course['course_name'];
                $id = $course['id'];
                $course_number = $course['course_number'];
                $new_course = new Course($course_name, $id, $course_number);
                array_push($courses, $new_course);
            }
            return $courses;
        }

        static function deleteAll() {
            $GLOBALS['DB']->exec("DELETE FROM courses;");
        }
        
        static function find($search_id){
            $found_course = null;
            $courses = Course::getAll();
            foreach($courses as $course) {
                $course_id = $course->getId();
                if ($course_id == $search_id) {
                    $found_course = $course;
                }
            }
            return $found_course;
        }
    }
?>
