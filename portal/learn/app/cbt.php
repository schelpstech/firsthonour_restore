<?php
include '../app/query.php';

function cbt_flash($type, $message)
{
    $class = $type === 'success' ? 'bg-success' : 'bg-danger';
    $_SESSION['msg'] =
        '<div class="alert text-white ' . $class . ' d-flex align-items-center justify-content-between" role="alert">
            <div class="alert-text">' . $message . '</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
}

function cbt_redirect($url)
{
    header('Location: ' . $url);
    exit;
}

if (!isset($_SESSION['active'], $_SESSION['user_type'], $_POST['action'])) {
    cbt_flash('error', 'Access denied. Please log in and try again.');
    cbt_redirect('../view/index.php');
}

$action = $_POST['action'];

if ($action === 'create_assessment' && $_SESSION['user_type'] === 'Instructor') {
    $title = trim($_POST['title'] ?? '');
    $classid = trim($_POST['classid'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $topic = (int)($_POST['topic'] ?? 0);
    $deadline = trim($_POST['deadline'] ?? '');
    $totalMark = (float)($_POST['total_mark'] ?? 0);
    $instruction = trim($_POST['instruction'] ?? '');

    if ($title === '' || $classid === '' || $subject === '' || $topic <= 0 || $totalMark <= 0) {
        cbt_flash('error', 'Kindly complete the CBT title, class, subject, topic and mark obtainable.');
        cbt_redirect('../app/router.php?pageid=resources&item=add_cbt');
    }

    $sql = 'INSERT INTO lhp_cbt_assessment
        (title, term, classid, sbjid, topicid, staffid, total_mark, deadline, instruction, status)
        VALUES (:title, :term, :classid, :sbjid, :topicid, :staffid, :total_mark, :deadline, :instruction, 1)';
    $query = $db_conn->prepare($sql);
    $query->execute([
        ':title' => $title,
        ':term' => $active_term['term'],
        ':classid' => $classid,
        ':sbjid' => $subject,
        ':topicid' => $topic,
        ':staffid' => $_SESSION['active'],
        ':total_mark' => $totalMark,
        ':deadline' => $deadline !== '' ? $deadline : null,
        ':instruction' => $instruction,
    ]);

    $cbtid = $db_conn->lastInsertId();
    cbt_flash('success', 'CBT assessment created. You can now add questions.');
    cbt_redirect('../app/router.php?pageid=resources&item=manage_cbt&item_ref=' . $cbtid);
}

if ($action === 'add_question' && $_SESSION['user_type'] === 'Instructor') {
    $cbtid = (int)($_POST['cbtid'] ?? 0);
    $question = trim($_POST['question_text'] ?? '');
    $optionA = trim($_POST['option_a'] ?? '');
    $optionB = trim($_POST['option_b'] ?? '');
    $optionC = trim($_POST['option_c'] ?? '');
    $optionD = trim($_POST['option_d'] ?? '');
    $correct = strtoupper(trim($_POST['correct_option'] ?? ''));
    $mark = (float)($_POST['mark'] ?? 0);

    if ($cbtid <= 0 || $question === '' || $optionA === '' || $optionB === '' || $optionC === '' || $optionD === '' || !in_array($correct, ['A', 'B', 'C', 'D'], true) || $mark <= 0) {
        cbt_flash('error', 'Kindly complete the question, options, correct answer and mark.');
        cbt_redirect('../app/router.php?pageid=resources&item=manage_cbt&item_ref=' . $cbtid);
    }

    $owner = $db_conn->prepare('SELECT cbtid FROM lhp_cbt_assessment WHERE cbtid = :cbtid AND staffid = :staffid AND status = 1 LIMIT 1');
    $owner->execute([':cbtid' => $cbtid, ':staffid' => $_SESSION['active']]);
    if (!$owner->fetch(PDO::FETCH_ASSOC)) {
        cbt_flash('error', 'You can only add questions to your own CBT assessment.');
        cbt_redirect('../app/router.php?pageid=resources&item=add_cbt');
    }

    $sql = 'INSERT INTO lhp_cbt_question
        (cbtid, question_text, option_a, option_b, option_c, option_d, correct_option, mark, status)
        VALUES (:cbtid, :question_text, :option_a, :option_b, :option_c, :option_d, :correct_option, :mark, 1)';
    $query = $db_conn->prepare($sql);
    $query->execute([
        ':cbtid' => $cbtid,
        ':question_text' => $question,
        ':option_a' => $optionA,
        ':option_b' => $optionB,
        ':option_c' => $optionC,
        ':option_d' => $optionD,
        ':correct_option' => $correct,
        ':mark' => $mark,
    ]);

    cbt_flash('success', 'Question added successfully.');
    cbt_redirect('../app/router.php?pageid=resources&item=manage_cbt&item_ref=' . $cbtid);
}

if ($action === 'remove_question' && $_SESSION['user_type'] === 'Instructor') {
    $cbtid = (int)($_POST['cbtid'] ?? 0);
    $questionid = (int)($_POST['questionid'] ?? 0);

    $sql = 'UPDATE lhp_cbt_question q
        INNER JOIN lhp_cbt_assessment a ON q.cbtid = a.cbtid
        SET q.status = 0
        WHERE q.questionid = :questionid AND q.cbtid = :cbtid AND a.staffid = :staffid';
    $query = $db_conn->prepare($sql);
    $query->execute([
        ':questionid' => $questionid,
        ':cbtid' => $cbtid,
        ':staffid' => $_SESSION['active'],
    ]);

    cbt_flash('success', 'Question removed from the CBT.');
    cbt_redirect('../app/router.php?pageid=resources&item=manage_cbt&item_ref=' . $cbtid);
}

if ($action === 'submit_cbt' && $_SESSION['user_type'] === 'Learner') {
    $cbtid = (int)($_POST['cbtid'] ?? 0);
    $answers = $_POST['answer'] ?? [];

    $check = $db_conn->prepare('SELECT attemptid FROM lhp_cbt_attempt WHERE cbtid = :cbtid AND learnerid = :learnerid LIMIT 1');
    $check->execute([':cbtid' => $cbtid, ':learnerid' => $_SESSION['active']]);
    if ($check->fetch(PDO::FETCH_ASSOC)) {
        cbt_flash('error', 'You have already submitted this CBT assessment.');
        cbt_redirect('../app/router.php?pageid=cbt&ref=' . $cbtid);
    }

    $sql = 'SELECT q.questionid, q.correct_option, q.mark
        FROM lhp_cbt_question q
        INNER JOIN lhp_cbt_assessment a ON q.cbtid = a.cbtid
        WHERE q.cbtid = :cbtid
            AND q.status = 1
            AND a.status = 1
            AND a.classid = :classid
        ORDER BY q.questionid ASC';
    $query = $db_conn->prepare($sql);
    $query->execute([
        ':cbtid' => $cbtid,
        ':classid' => $learner_profile['classid'],
    ]);
    $questions = $query->fetchAll(PDO::FETCH_ASSOC);

    if (empty($questions)) {
        cbt_flash('error', 'This CBT is not available for your class.');
        cbt_redirect('../app/router.php?pageid=subject');
    }

    $score = 0;
    $total = 0;
    foreach ($questions as $question) {
        $total += (float)$question['mark'];
        $selected = strtoupper(trim($answers[$question['questionid']] ?? ''));
        if ($selected === $question['correct_option']) {
            $score += (float)$question['mark'];
        }
    }

    $db_conn->beginTransaction();
    $attempt = $db_conn->prepare('INSERT INTO lhp_cbt_attempt (cbtid, learnerid, score, total_mark) VALUES (:cbtid, :learnerid, :score, :total_mark)');
    $attempt->execute([
        ':cbtid' => $cbtid,
        ':learnerid' => $_SESSION['active'],
        ':score' => $score,
        ':total_mark' => $total,
    ]);
    $attemptid = $db_conn->lastInsertId();

    $answerInsert = $db_conn->prepare('INSERT INTO lhp_cbt_answer
        (attemptid, questionid, selected_option, is_correct, mark_awarded)
        VALUES (:attemptid, :questionid, :selected_option, :is_correct, :mark_awarded)');

    foreach ($questions as $question) {
        $selected = strtoupper(trim($answers[$question['questionid']] ?? ''));
        $isCorrect = $selected === $question['correct_option'] ? 1 : 0;
        $answerInsert->execute([
            ':attemptid' => $attemptid,
            ':questionid' => $question['questionid'],
            ':selected_option' => in_array($selected, ['A', 'B', 'C', 'D'], true) ? $selected : null,
            ':is_correct' => $isCorrect,
            ':mark_awarded' => $isCorrect ? $question['mark'] : 0,
        ]);
    }
    $db_conn->commit();

    cbt_flash('success', 'CBT submitted successfully. Your score is ' . $score . ' / ' . $total . '.');
    cbt_redirect('../app/router.php?pageid=cbt&ref=' . $cbtid);
}

cbt_flash('error', 'Invalid CBT request.');
cbt_redirect('../app/router.php?pageid=subject');
