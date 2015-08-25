<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Course.php";
    require_once "src/Course.php";

    $server = 'mysql:host=localhost;dbname=university_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class CourseTest extends PHPUnit_Framework_TestCase {

        protected function tearDown() {
            Student::deleteAll();
            Course::deleteAll();
        }

        function testGetCourseName() {
            //Arrange
            $course_name = "History 110";
            $id = null;
            $course_number = "HST110";
            $test_course = new Course($course_name, $id, $course_number);

            //Act
            $result = $test_course->getCourseName();

            //Assert
            $this->assertEquals($course_name, $result);
        }

        function testSetCourseName() {
            //Arrange
            $course_name = "History 110";
            $id = null;
            $course_number = "HST110";
            $test_course = new Course($course_name, $id, $course_number);

            //Act
            $test_course->setCourseName("Math 101");
            $result = $test_course->getCourseName();

            //Assert
            $this->assertEquals("Math 101", $result);
        }

        function test_getId() {
            //Arrange
            $course_name = "History 110";
            $id = 1;
            $course_number = "HST110";
            $test_course = new Course ($course_name, $id, $course_number);

            //Act
            $result = $test_course->getId();

            //Assert
            $this->assertEquals(1, $result);
        }

        function test_save() {
            //Arrange
            $course_name = "History 110";
            $id = 1;
            $course_number = "HST110";
            $test_course = new Course ($course_name, $id, $course_number);

            //Act
            $test_course->save();

            //Assert
            $result = Course::getAll();
            $this->assertEquals($test_course, $result[0]);
        }

        function testSaveSetsId () {
            //Arrange
            $course_name = "History 110";
            $id = 1;
            $course_number = "HST110";
            $test_course = new Course ($course_name, $id, $course_number);

            //Act
            $test_course->save();

            //Assert
            $this->assertEquals(true, is_numeric($test_course->getId()));
        }

        function test_getAll() {
            //Arrange
            $course_name = "History 110";
            $id = 1;
            $course_number = "HST110";
            $test_course = new Course ($course_name, $id, $course_number);
            $test_course->save();

            $course_name2 = "Math 101";
            $id2 = 2;
            $course_number2 = "MTH101";
            $test_course2 = new Course ($course_name2, $id2, $course_number2);
            $test_course2->save();

            //Act
            $result = Course::getAll();

            //Assert
            $this->assertEquals([$test_course, $test_course2], $result);
        }

        function test_deleteAll() {
            //Arrange
            $course_name = "History 110";
            $id = 1;
            $course_number = "HST110";
            $test_course = new Course ($course_name, $id, $course_number);
            $test_course->save();

            $course_name2 = "Math 101";
            $id2 = 2;
            $course_number2 = "MTH101";
            $test_course2 = new Course ($course_name2, $id2, $course_number2);
            $test_course2->save();

            //Act
            Course::deleteAll();

            //Assert
            $result = Course::getAll();
            $this->assertEquals([], $result);
        }

        function test_find() {
            //Arrange
            $course_name = "History 110";
            $id = 1;
            $course_number = "HST110";
            $test_course = new Course ($course_name, $id, $course_number);
            $test_course->save();

            $course_name2 = "Math 101";
            $id2 = 2;
            $course_number2 = "MTH101";
            $test_course2 = new Course ($course_name2, $id2, $course_number2);
            $test_course2->save();

            //Act
            $id = $test_course->getId();
            $result = Course::find($id);

            //Assert
            $this->assertEquals($test_course, $result);
        }

        function testUpdate() {
            //Arrange
            $course_name = "History 110";
            $id = 1;
            $course_number = "HST110";
            $test_course = new Course ($course_name, $id, $course_number);
            $test_course->save();
            $new_course_name = "Math 101";

            //Act
            $test_course->update($new_course_name);

            //Assert
            $this->assertEquals($new_course_name, $test_course->getCourseName());
        }

        function testDeletecourse() {
            //Arrange
            $course_name = "History 110";
            $id = 1;
            $course_number = "HST110";
            $test_course = new Course ($course_name, $id, $course_number);
            $test_course->save();

            $course_name2 = "Math 101";
            $id2 = 2;
            $course_number2 = "MTH101";
            $test_course2 = new Course ($course_name2, $id2, $course_number2);
            $test_course2->save();

            //Act
            $test_course->deleteOne();

            //Assert
            $this->assertEquals([$test_course2], Course::getAll());
        }

        function testAddStudent()
        {
            //Arrange
            $course_name = "History";
            $id = 1;
            $course_number = "HIST101";
            $test_course = new Course($course_name, $id, $course_number);
            $test_course->save();

            $student_name = "Paco";
            $id2 = 2;
            $enrollment_date = "2015-03-22";
            $test_student = new Student($student_name, $id2, $enrollment_date);
            $test_student->save();

            //Act
            $test_course->addStudent($test_student);
            $result = $test_course->getStudents();

            //Assert
            $this->assertEquals([$test_student], $result);
        }

        function testGetStudents()
        {
            //Arrange
            $course_name = "History";
            $id = 1;
            $course_number = "HIST101";
            $test_course = new Course($course_name, $id, $course_number);
            $test_course->save();

            $student_name2 = "Pablo";
            $enrollment_date2 = "2015-08-22";
            $id2 = 2;
            $test_student2 = new Student($student_name2, $id2, $enrollment_date2);
            $test_student2->save();

            $student_name = "Paco";
            $id3 = 3;
            $enrollment_date = "2015-06-22";
            $test_student = new Student($student_name, $id3, $enrollment_date);
            $test_student->save();

            //Act
            $test_course->addStudent($test_student);
            $test_course->addStudent($test_student2);

            //Assert
            $this->assertEquals($test_course->getStudents(), [$test_student, $test_student2]);
        }

        function testDelete() {
            //Arrange
            $course_name = "History";
            $id = 1;
            $course_number = "HIST101";
            $test_course = new Course($course_name, $id, $course_number);
            $test_course->save();

            $student_name = "Paco";
            $id2 = 2;
            $enrollment_date = "2015-05-11";
            $test_student = new Student($student_name, $id2, $enrollment_date);
            $test_student->save();

            //Act
            $test_course->addStudent($test_student);
            $test_course->deleteOne();

            //Assert
            $this->assertEquals([], $test_student->getCourses());
        }


        //Finished all course tests

    }

?>
