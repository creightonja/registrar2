<?php

    //Loading class functionality
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Student.php";
    require_once __DIR__."/../src/Course.php";

    //Silex preloads
    $app = new Silex\Application();
    $app['debug'] = true;

    //PDO setup
    $server = 'mysql:host=localhost;dbname=university';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    //Patch and delete functions from symfony
    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();


    //Use silex to load page and twig path
    $app->register(new Silex\Provider\TwigServiceProvider(), array(
                    'twig.path' => __DIR__.'/../views'
    ));

    //Index page rendering links to courses and students
    $app->get("/", function() use ($app) {
        return $app['twig']->render('index.html.twig', array('courses' => Course::getAll(), 'students' => Student::getAll()));
    });

    //Begin Student Functionality

    //Students page, lists, add, edit, or delete a student links.
    $app->get("/students", function() use ($app) {
        return $app['twig']->render('students.html.twig', array('students' => Student::getAll()));
    });

    //Adds a new student to DB, renders to students.html
    $app->post("/students", function() use ($app) {
        $student_name = $_POST['student_name'];
        $enrollment_date = $_POST['enrollment_date'];
        $student = new Student($student_name, $id=null, $enrollment_date);
        $student->save();
        return $app['twig']->render('students.html.twig', array('students' => Student::getAll()));
    });

    //Showing a student's schedule of courses.  Renders to particular student's page with crud function
    $app->get("/students/{id}", function($id) use ($app) {
        $student = Student::find($id);
        return $app['twig']->render('student.html.twig', array('student' => $student, 'courses' => $student->getCourses(), 'all_courses' => Course::getAll()));
    });

    //Linking student to a course
    $app->post("/add_students", function() use ($app) {
        $course = Course::find($_POST['course_id']);
        $student = Student::find($_POST['student_id']);
        $course->addStudent($student);
        return $app['twig']->render('course.html.twig', array('course' => $course, 'courses' => Course::getAll(), 'students' => $course->getStudents(), 'all_students' => Student::getAll()));
    });

    //Updates student, comes from student.html, posts back to students.html with updated student info
    $app->patch("/student/{id}/edit", function($id) use ($app){
        $new_student_name = $_POST['new_student_name'];
        $student = Student::find($id);
        $student->update($new_student_name);
        return $app['twig']->render('students.html.twig', array('students' => Student::getAll()));
    });
    //Deletes student, comes from student.html, posts back to students.html minus deleted student
    $app->delete("/student/{id}/edit", function($id) use ($app) {
        $student = Student::find($id);
        $student->deleteOne();
        return $app['twig']->render('students.html.twig', array('students' => Student::getAll()));
    });

    //Delete All Students from DB
    $app->post("/delete_students", function() use ($app) {
        Student::deleteAll();
        return $app['twig']->render('index.html.twig');
    });

    // -------------------------End Student Routes -------------------------


    // -------------------------Begin Course Routes -------------------------



    //Main courses page, displays all courses.
    $app->get("/courses", function() use ($app) {
        return $app['twig']->render('courses.html.twig', array('courses' => Course::getAll()));
    });

    //Adds a new course to courses table.
    $app->post("/courses", function() use ($app) {
        $id = null;
        $course = new Course($_POST['course_name'], $id, $_POST['course_number']);
        $course->save();
        return $app['twig']->render('courses.html.twig', array('courses' => Course::getAll()));
    });

    //Listing all students for a selected course. Check all_students variable for error issue
    $app->get("/courses/{id}", function($id) use ($app) {
        $course = Course::find($id);
        return $app['twig']->render('course.html.twig', array('course' => $course, 'students' => $course->getStudents(), 'all_students' => Student::getAll()));
    });

    //
    $app->post("/add_courses", function() use ($app) {
        $course = Course::find($_POST['course_id']);
        $student = Student::find($_POST['student_id']);
        $student->addCourse($course);
        return $app['twig']->render('student.html.twig', array('student' => $student, 'students' => Student::getAll(), 'courses' => $student->getCourses(), 'all_courses' => Course::getAll()));
    });


    //
    $app->post("/delete_courses", function() use ($app) {
        Course::deleteAll();
        return $app['twig']->render('index.html.twig');
    });

    //Updates course, comes from course.html, posts back to courses.html
    $app->patch("/course/{id}/edit", function($id) use ($app){
        $new_course_name = $_POST['new_course_name'];
        $course = Course::find($id);
        $course->update($new_course_name);
        return $app['twig']->render('courses.html.twig', array('courses' => Course::getAll()));
    });
    //Deletes course, comes from course.html, posts back to courses.html
    $app->delete("/course/{id}/edit", function($id) use ($app) {
        $course = Course::find($id);
        $course->deleteOne();
        return $app['twig']->render('courses.html.twig', array('courses' => Course::getAll()));
    });
    return $app;
?>
