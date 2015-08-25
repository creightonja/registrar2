<?php

    class Student
    {
        private $student_name;
        private $id;
        private $enrollment_date;

        function __construct($student_name, $id = null, $enrollment_date)
        {
            $this->student_name = $student_name;
            $this->id = $id;
            $this->enrollment_date = $enrollment_date;
        }

        function setStudentName($new_student_name)
        {
            $this->student_name = (string) $new_student_name;
        }

        function getStudentName()
        {
            return $this->student_name;
        }

        function getId()
        {
            return $this->id;
        }

        function getEnrollmentDate() {
            return $this->enrollment_date;
        }

        function save()
        {
            $statement = $GLOBALS['DB']->exec("INSERT INTO students (student_name, enrollment_date) VALUES ('{$this->getStudentName()}', '{$this->getEnrollmentDate()}')");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        function update($new_student_name) {
            $GLOBALS['DB']->exec("UPDATE students SET student_name = '{$new_student_name}' WHERE id = {$this->getId()};");
            $this->setStudentName($new_student_name);
        }

        function deleteOne() {
            $GLOBALS['DB']->exec("DELETE FROM students WHERE id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM classes_taken WHERE student_id = {$this->getId()};");
        }

        function addCourse($course)
        {
            $GLOBALS['DB']->exec("INSERT INTO classes_taken (student_id, course_id) VALUES ({$this->getId()}, {$course->getId()});");
        }

        function getCourses()
        {
            //join statement
            $selected_course = $GLOBALS['DB']->query("SELECT courses.* FROM
                students JOIN classes_taken ON (students.id = classes_taken.student_id)
                         JOIN courses ON (classes_taken.course_id = courses.id)
                         WHERE students.id = {$this->getId()};");
            //convert output of the join statement into an array
            $found_courses = $selected_course->fetchAll(PDO::FETCH_ASSOC);
            $student_courses = array();
            foreach($found_courses as $found_course) {
                $course_name = $found_course['course_name'];
                $id = $found_course['id'];
                $course_number = $found_course['course_number'];
                $new_course = new Course($course_name, $id, $course_number);
                array_push($student_courses, $new_course);
            }
            return $student_courses;
        }

        static function getAll()
        {
            $returned_students = $GLOBALS['DB']->query("SELECT * FROM students ORDER BY enrollment_date;");
            $students = array();
            foreach($returned_students as $student) {
                $student_name = $student['student_name'];
                $id = $student['id'];
                $enrollment_date = $student['enrollment_date'];
                $new_student = new Student($student_name, $id, $enrollment_date);
                array_push($students, $new_student);
            }
            return $students;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM students;");
        }

        static function find($search_id) {
            $found_student = null;
            $students = Student::getAll();
            foreach($students as $student) {
                $student_id = $student->getId();
                if ($student_id == $search_id) {
                    $found_student = $student;
                }
            }
            return $found_student;
        }

    }
?>
