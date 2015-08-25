<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Student.php";
    require_once "src/Course.php";

    $server = 'mysql:host=localhost;dbname=university_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class StudentTest extends PHPUnit_Framework_TestCase {

        protected function tearDown() {
            Student::deleteAll();
            Course::deleteAll();
        }

        function testGetStudentName() {
            //Arrange
            $student_name = "Paco";
            $id = null;
            $enrollment_date = null;
            $test_student = new Student($student_name, $id, $enrollment_date);

            //Act
            $result = $test_student->getStudentName();

            //Assert
            $this->assertEquals($student_name, $result);
        }

        function testSetStudentName() {
            //Arrange
            $student_name = "Paco";
            $id = null;
            $enrollment_date = null;
            $test_student = new Student($student_name, $id, $enrollment_date);

            //Act
            $test_student->setStudentName("Pablo");
            $result = $test_student->getStudentName();

            //Assert
            $this->assertEquals("Pablo", $result);
        }

        function test_getId() {
            //Arrange
            $student_name = "Paco";
            $id = 1;
            $enrollment_date = null;
            $test_student = new Student ($student_name, $id, $enrollment_date);

            //Act
            $result = $test_student->getId();

            //Assert
            $this->assertEquals(1, $result);
        }

        function test_save() {
            //Arrange
            $student_name = "Paco";
            $id = 1;
            $enrollment_date = "2015-04-22";
            $test_student = new Student($student_name, $id, $enrollment_date);

            //Act
            $test_student->save();

            //Assert
            $result = Student::getAll();
            $this->assertEquals($test_student, $result[0]);
        }

        function testSaveSetsId () {
            //Arrange
            $student_name = "Paco";
            $id = 1;
            $enrollment_date = "2015-04-22";
            $test_student = new Student ($student_name, $id, $enrollment_date);

            //Act
            $test_student->save();

            //Assert
            $this->assertEquals(true, is_numeric($test_student->getId()));
        }

        function test_getAll() {
            //Arrange
            $student_name = "Paco";
            $enrollment_date = "2015-08-22";
            $id = 1;
            $test_Student = new Student($student_name, $id, $enrollment_date);
            $test_Student->save();

            $student_name2 = "Pablo";
            $enrollment_date2 = "2015-08-24";
            $id2 = 2;
            $test_Student2 = new Student($student_name2, $id2, $enrollment_date2);
            $test_Student2->save();

            //Act
            $result = Student::getAll();

            //Assert
            $this->assertEquals([$test_Student, $test_Student2], $result);
        }

        function test_deleteAll() {
            //Arrange
            $student_name = "Paco";
            $enrollment_date = "2015-08-25";
            $id = 1;
            $test_Student = new Student($student_name, $id, $enrollment_date);
            $test_Student->save();

            $student_name2 = "Pablo";
            $enrollment_date2 = "2015-08-22";
            $id2 = 2;
            $test_Student2 = new Student($student_name2, $id2, $enrollment_date2);
            $test_Student2->save();

            //Act
            Student::deleteAll();

            //Assert
            $result = Student::getAll();
            $this->assertEquals([], $result);
        }

        function test_find() {
            //Arrange
            $student_name = "Paco";
            $enrollment_date = "2015-08-22";
            $id1=1;
            $test_Student = new Student($student_name, $id1, $enrollment_date);
            $test_Student->save();

            $student_name2 = "Pablo";
            $enrollment_date2 = "2015-08-25";
            $id2 = 2;
            $test_Student2 = new Student($student_name2, $id2, $enrollment_date2);
            $test_Student2->save();

            //Act
            $id = $test_Student->getId();
            $result = Student::find($id);

            //Assert
            $this->assertEquals($test_Student, $result);
        }

        function testUpdate() {
            //Arrange
            $student_name = "Paco";
            $id = 1;
            $enrollment_date = "2015-08-29";
            $test_student = new Student($student_name, $id, $enrollment_date);
            $test_student->save();
            $new_student_name = "Pablo";

            //Act
            $test_student->update($new_student_name);

            //Assert
            $this->assertEquals("Pablo", $test_student->getStudentName());
        }

        function testDeleteStudent() {
            //Arrange
            $student_name = "Paco";
            $id = 1;
            $enrollment_date = "2015-08-29";
            $test_student = new Student($student_name, $id, $enrollment_date);
            $test_student->save();

            $student_name2 = "Pablo";
            $id2 = 2;
            $enrollment_date2 = "2015-08-30";
            $test_student2 = new Student($student_name2, $id2, $enrollment_date2);
            $test_student2->save();

            //Act
            $test_student->deleteOne();

            //Assert
            $this->assertEquals([$test_student2], Student::getAll());
        }

        function testAddCourse()
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
            $test_student->addCourse($test_course);
            $result = $test_student->getCourses();

            //Assert
            $this->assertEquals([$test_course], $result);
        }

        function testGetCourses()
        {
            //Arrange
            $course_name = "History";
            $id = 1;
            $course_number = "HIST101";
            $test_course = new Course($course_name, $id, $course_number);
            $test_course->save();

            $course_name2 = "Economics";
            $id2 = 2;
            $course_number = "ECON101";
            $test_course2 = new Course($course_name2, $id2, $course_number);
            $test_course2->save();

            $student_name = "Paco";
            $id3 = 3;
            $enrollment_date = "2015-06-22";
            $test_student = new Student($student_name, $id3, $enrollment_date);
            $test_student->save();

            //Act
            $test_student->addCourse($test_course);
            $test_student->addCourse($test_course2);

            //Assert
            $this->assertEquals($test_student->getCourses(), [$test_course, $test_course2]);
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
            $test_student->addCourse($test_course);
            $test_student->deleteOne();

            //Assert
            $this->assertEquals([], $test_course->getStudents());
        }
            //Finished all student tests
    }
?>
