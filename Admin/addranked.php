<?php
// Include admin environment file if needed
include '../Constants/Combine-admin.php';

// Include DB connection
require '../Database/connection.php';

// Capture the 'level' parameter from the URL safely
$level = isset($_GET['level']) ? htmlspecialchars($_GET['level']) : 'Level';

// Initialize variables for error and success messages
$error = '';
$success = '';

// Fetch all levels from the database to populate the dropdown
$levels = [];
$sql_levels = "SELECT ranked_quiz_id, ranked_quiz_level, ranked_quiz_name FROM ranked_quiz_levels ORDER BY ranked_quiz_id ASC";
$result_levels = $conn->query($sql_levels);
if ($result_levels && $result_levels->num_rows > 0) {
    while ($row = $result_levels->fetch_assoc()) {
        $levels[] = $row;
    }
} else {
    // If no levels found, $levels stays empty
    // Admin should create levels first before adding quizzes
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $quiz_name = isset($_POST['quiz_name']) ? trim($_POST['quiz_name']) : '';
    $quiz_description = isset($_POST['quiz_description']) ? trim($_POST['quiz_description']) : '';
    $ranked_quiz_id = isset($_POST['ranked_quiz_id']) ? (int)$_POST['ranked_quiz_id'] : 0;
    $questions_data = isset($_POST['questions']) && is_array($_POST['questions']) ? $_POST['questions'] : [];

    // Basic validation
    if (empty($quiz_name)) {
        $error = "Quiz Name is required.";
    } elseif (empty($quiz_description)) {
        $error = "Quiz Description is required.";
    } elseif ($ranked_quiz_id <= 0) {
        $error = "Please select a valid quiz level.";
    } else {
        // Validate questions
        foreach ($questions_data as $index => $q) {
            $q_text = isset($q['question_text']) ? trim($q['question_text']) : '';
            $q_optionA = isset($q['option_A']) ? trim($q['option_A']) : '';
            $q_optionB = isset($q['option_B']) ? trim($q['option_B']) : '';
            $q_optionC = isset($q['option_C']) ? trim($q['option_C']) : '';
            $q_optionD = isset($q['option_D']) ? trim($q['option_D']) : '';
            $q_correct = isset($q['correct_option']) ? trim($q['correct_option']) : '';

            if (empty($q_text) || empty($q_optionA) || empty($q_optionB) || empty($q_optionC) || empty($q_optionD) || empty($q_correct)) {
                $error = "All fields are required for each question (Question " . ($index + 1) . ").";
                break;
            }

            if (!in_array($q_correct, ['A', 'B', 'C', 'D'])) {
                $error = "Please select a valid correct option (A, B, C, or D) for Question " . ($index + 1) . ".";
                break;
            }
        }
    }
}
//     // If no errors, proceed with database insert
//     if (empty($error)) {
//         // Insert into custom_quiz table
//         $quiz_code = '0001'; // Using a string to avoid leading zero issues
//         $custom_quiz_score = 100;
//         $educator_id = 1; // You might get this from session or admin context

//         $insert_quiz_sql = "INSERT INTO custom_quiz (quiz_name, quiz_description, quiz_code, custom_quiz_score, educator_id) VALUES (?, ?, ?, ?, ?)";
//         $stmt_quiz = $conn->prepare($insert_quiz_sql);
//         if (!$stmt_quiz) {
//             $error = "Database error preparing quiz insert: " . $conn->error;
//         } else {
//             $stmt_quiz->bind_param("ssiii", $quiz_name, $quiz_description, $quiz_code, $custom_quiz_score, $educator_id);
//             if ($stmt_quiz->execute()) {
//                 // Get the inserted quiz_id
//                 $new_quiz_id = $stmt_quiz->insert_id;
//                 $stmt_quiz->close();

//                 // Insert questions into questions table
//                 $insert_question_sql = "INSERT INTO questions (question_id, question_no, question_text, option_A, option_B, option_C, option_D, correct_option, ranked_quiz_id, quiz_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
//                 $stmt_question = $conn->prepare($insert_question_sql);

//                 if (!$stmt_question) {
//                     $error = "Database error preparing questions insert: " . $conn->error;
//                 } else {
//                     foreach ($questions_data as $index => $q) {
//                         $question_no = $index + 1; // Question number starts from 1
//                         $q_text = trim($q['question_text']);
//                         $q_optionA = trim($q['option_A']);
//                         $q_optionB = trim($q['option_B']);
//                         $q_optionC = trim($q['option_C']);
//                         $q_optionD = trim($q['option_D']);
//                         $q_correct = trim($q['correct_option']);

//                         // Ensure the level is set correctly to "L1", "L2", etc.
//                         $level = "L" . $ranked_quiz_id;  // Assuming $ranked_quiz_id is the level number

//                         // Generate question_id in the format "L1Q1", "L1Q2", etc.
//                         $question_id = $level . "Q" . $question_no;

//                         // Check if the generated question_id already exists in the database
//                         $check_question_sql = "SELECT COUNT(*) FROM questions WHERE question_id = ?";
//                         $stmt_check = $conn->prepare($check_question_sql);
//                         $stmt_check->bind_param("s", $question_id);
//                         $stmt_check->execute();
//                         $stmt_check->bind_result($count);
//                         $stmt_check->fetch();
//                         $stmt_check->close();

//                         // If the question_id already exists, skip the insertion or handle the error
//                         if ($count > 0) {
//                             $error = "Question ID $question_id already exists in the database.";
//                             break;
//                         } else {
//                             // Proceed with inserting the question
//                             $stmt_question->bind_param(
//                                 "sissssssii",
//                                 $question_id,      // This is the newly formatted question_id
//                                 $question_no,
//                                 $q_text,
//                                 $q_optionA,
//                                 $q_optionB,
//                                 $q_optionC,
//                                 $q_optionD,
//                                 $q_correct,
//                                 $ranked_quiz_id,
//                                 $new_quiz_id
//                             );

//                             if (!$stmt_question->execute()) {
//                                 $error = "Database error inserting question $question_no: " . $stmt_question->error;
//                                 break;
//                             }
//                         }
//                     }

//                     $stmt_question->close();
//                 }

//                 // If no question errors, set success message
//                 if (empty($error)) {
//                     $success = "Quiz and questions have been created successfully!";
//                 }
//             } else {
//                 $error = "Database error inserting quiz: " . $stmt_quiz->error;
//                 $stmt_quiz->close();
//             }
//         }
//     }
// }

// If no errors, proceed with database insert
if (empty($error)) {
    // Insert questions into questions table
    $insert_question_sql = "INSERT INTO questions (question_id, question_no, question_text, option_A, option_B, option_C, option_D, correct_option, ranked_quiz_id, quiz_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_question = $conn->prepare($insert_question_sql);

    if (!$stmt_question) {
        $error = "Database error preparing questions insert: " . $conn->error;
    } else {
        foreach ($questions_data as $index => $q) {
            $question_no = $index + 1; // Question number starts from 1
            $q_text = trim($q['question_text']);
            $q_optionA = trim($q['option_A']);
            $q_optionB = trim($q['option_B']);
            $q_optionC = trim($q['option_C']);
            $q_optionD = trim($q['option_D']);
            $q_correct = trim($q['correct_option']);

            // Ensure the level is set correctly to "L1", "L2", etc.
            $level = "L" . $ranked_quiz_id;  // Assuming $ranked_quiz_id is the level number

            // Generate question_id in the format "L1Q1", "L1Q2", etc.
            $question_id = $level . "Q" . $question_no;

            // Check if the generated question_id already exists in the database
            $check_question_sql = "SELECT COUNT(*) FROM questions WHERE question_id = ?";
            $stmt_check = $conn->prepare($check_question_sql);
            $stmt_check->bind_param("s", $question_id);
            $stmt_check->execute();
            $stmt_check->bind_result($count);
            $stmt_check->fetch();
            $stmt_check->close();

            // If the question_id already exists, skip the insertion or handle the error
            if ($count > 0) {
                $error = "Question ID $question_id already exists in the database.";
                break;
            } else {
                // Proceed with inserting the question
                $stmt_question->bind_param(
                    "sissssssii",
                    $question_id,      // This is the newly formatted question_id
                    $question_no,
                    $q_text,
                    $q_optionA,
                    $q_optionB,
                    $q_optionC,
                    $q_optionD,
                    $q_correct,
                    $ranked_quiz_id,
                    $new_quiz_id // This value can be retrieved from elsewhere (or you might need to remove this if it's unnecessary)
                );

                if (!$stmt_question->execute()) {
                    $error = "Database error inserting question $question_no: " . $stmt_question->error;
                    break;
                }
            }
        }

        $stmt_question->close();
    }

    // If no question errors, set success message
    if (empty($error)) {
        $success = "Questions have been created successfully!";
    }
}

$conn->close();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Ranked Levels</title>
    <link rel="stylesheet" href="addranked.css">
</head>

<body>
    <div class="container">
        <h1>Create a Ranked Quiz Level</h1>

        <?php if (!empty($error)): ?>
            <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form action="addranked.php" method="POST" id="quizForm">
            <div class="form-group">
                <label for="quiz_name">Quiz Name:</label>
                <input type="text" id="quiz_name" name="quiz_name" required value="<?php echo isset($_POST['quiz_name']) ? htmlspecialchars($_POST['quiz_name'], ENT_QUOTES, 'UTF-8') : ''; ?>">
            </div>

            <div class="form-group">
                <label for="quiz_description">Quiz Description:</label>
                <textarea id="quiz_description" name="quiz_description" rows="4" required><?php echo isset($_POST['quiz_description']) ? htmlspecialchars($_POST['quiz_description'], ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
            </div>

            <div class="form-group">
                <label for="ranked_quiz_id">Quiz Level:</label>
                <select id="ranked_quiz_id" name="ranked_quiz_id" required>
                    <option value="">--Select Level--</option>
                    <?php foreach ($levels as $lvl): ?>
                        <option value="<?php echo (int)$lvl['ranked_quiz_id']; ?>" <?php if (isset($_POST['ranked_quiz_id']) && (int)$_POST['ranked_quiz_id'] == $lvl['ranked_quiz_id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($lvl['ranked_quiz_level'] . " - " . $lvl['ranked_quiz_name'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <h2>Questions</h2>
            <div id="questionsContainer">
                <?php
                // $level = isset($_POST['ranked_quiz_id']) ? 'L' . $_POST['ranked_quiz_id'] : 'L1';


                // If form was submitted with errors, repopulate questions
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['questions'])) {
                    $level = 'L' . $_POST['ranked_quiz_id'];
                    foreach ($_POST['questions'] as $index => $q) {
                        $q_text = htmlspecialchars($q['question_text'], ENT_QUOTES, 'UTF-8');
                        $q_optionA = htmlspecialchars($q['option_A'], ENT_QUOTES, 'UTF-8');
                        $q_optionB = htmlspecialchars($q['option_B'], ENT_QUOTES, 'UTF-8');
                        $q_optionC = htmlspecialchars($q['option_C'], ENT_QUOTES, 'UTF-8');
                        $q_optionD = htmlspecialchars($q['option_D'], ENT_QUOTES, 'UTF-8');
                        $q_correct = isset($q['correct_option']) ? htmlspecialchars($q['correct_option'], ENT_QUOTES, 'UTF-8') : '';
                ?>
                        <div class="question-block">
                            <h3>Question <?php echo $index + 1; ?></h3>
                            <div class="form-group">
                                <label>Question Text:</label>
                                <textarea name="questions[<?php echo $index; ?>][question_text]" rows="3" required><?php echo $q_text; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Option A:</label>
                                <input type="text" name="questions[<?php echo $index; ?>][option_A]" required value="<?php echo $q_optionA; ?>">
                            </div>
                            <div class="form-group">
                                <label>Option B:</label>
                                <input type="text" name="questions[<?php echo $index; ?>][option_B]" required value="<?php echo $q_optionB; ?>">
                            </div>
                            <div class="form-group">
                                <label>Option C:</label>
                                <input type="text" name="questions[<?php echo $index; ?>][option_C]" required value="<?php echo $q_optionC; ?>">
                            </div>
                            <div class="form-group">
                                <label>Option D:</label>
                                <input type="text" name="questions[<?php echo $index; ?>][option_D]" required value="<?php echo $q_optionD; ?>">
                            </div>
                            <div class="form-group">
                                <label>Correct Option:</label>
                                <select name="questions[<?php echo $index; ?>][correct_option]" required>
                                    <option value="">--Select Correct Option--</option>
                                    <option value="A" <?php if ($q_correct === 'A') echo 'selected'; ?>>A</option>
                                    <option value="B" <?php if ($q_correct === 'B') echo 'selected'; ?>>B</option>
                                    <option value="C" <?php if ($q_correct === 'C') echo 'selected'; ?>>C</option>
                                    <option value="D" <?php if ($q_correct === 'D') echo 'selected'; ?>>D</option>
                                </select>
                            </div>
                            <button type="button" class="remove-question">Remove Question</button>
                            <hr>
                        </div>
                    <?php
                    }
                } else {
                    // Default 5 question blocks
                    for ($i = 0; $i < 5; $i++) {
                    ?>
                        <div class="question-block">
                            <h3>Question <?php echo $i + 1; ?></h3>
                            <div class="form-group">
                                <label>Question Text:</label>
                                <textarea name="questions[<?php echo $i; ?>][question_text]" rows="3" required></textarea>
                            </div>
                            <div class="form-group">
                                <label>Option A:</label>
                                <input type="text" name="questions[<?php echo $i; ?>][option_A]" required>
                            </div>
                            <div class="form-group">
                                <label>Option B:</label>
                                <input type="text" name="questions[<?php echo $i; ?>][option_B]" required>
                            </div>
                            <div class="form-group">
                                <label>Option C:</label>
                                <input type="text" name="questions[<?php echo $i; ?>][option_C]" required>
                            </div>
                            <div class="form-group">
                                <label>Option D:</label>
                                <input type="text" name="questions[<?php echo $i; ?>][option_D]" required>
                            </div>
                            <div class="form-group">
                                <label>Correct Option:</label>
                                <select name="questions[<?php echo $i; ?>][correct_option]" required>
                                    <option value="">--Select Correct Option--</option>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                    <option value="D">D</option>
                                </select>
                            </div>
                            <button type="button" class="remove-question">Remove Question</button>
                            <hr>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
            <button type="button" id="addQuestionBtn">Add More Questions</button>

            <div class="form-group">
                <button type="submit">Create Quiz</button>
            </div>
        </form>
    </div>

    <!-- JavaScript Section -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const questionsContainer = document.getElementById('questionsContainer');
            const addQuestionBtn = document.getElementById('addQuestionBtn');
            const quizForm = document.getElementById('quizForm');

            // Function to update the numbering of questions
            function updateQuestionNumbers() {
                const questionBlocks = document.querySelectorAll('.question-block');
                questionBlocks.forEach((block, index) => {
                    const heading = block.querySelector('h3');
                    heading.textContent = `Question ${index + 1}`;
                });
            }

            // Event delegation for removing questions
            questionsContainer.addEventListener('click', function(event) {
                if (event.target && event.target.classList.contains('remove-question')) {
                    event.preventDefault();
                    const questionBlock = event.target.closest('.question-block');
                    if (questionBlock) {
                        questionBlock.remove();
                        updateQuestionNumbers();
                    }
                }
            });

            // Function to add a new question block
            function addQuestion() {
                const questionCount = document.querySelectorAll('.question-block').length;
                const newIndex = questionCount;

                const newQuestionBlock = document.createElement('div');
                newQuestionBlock.classList.add('question-block');

                newQuestionBlock.innerHTML = `
                    <h3>Question ${newIndex + 1}</h3>
                    <div class="form-group">
                        <label>Question Text:</label>
                        <textarea name="questions[${newIndex}][question_text]" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Option A:</label>
                        <input type="text" name="questions[${newIndex}][option_A]" required>
                    </div>
                    <div class="form-group">
                        <label>Option B:</label>
                        <input type="text" name="questions[${newIndex}][option_B]" required>
                    </div>
                    <div class="form-group">
                        <label>Option C:</label>
                        <input type="text" name="questions[${newIndex}][option_C]" required>
                    </div>
                    <div class="form-group">
                        <label>Option D:</label>
                        <input type="text" name="questions[${newIndex}][option_D]" required>
                    </div>
                    <div class="form-group">
                        <label>Correct Option:</label>
                        <select name="questions[${newIndex}][correct_option]" required>
                            <option value="">--Select Correct Option--</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                        </select>
                    </div>
                    <button type="button" class="remove-question">Remove Question</button>
                    <hr>
                `;
                questionsContainer.appendChild(newQuestionBlock);
            }

            // Event listener for adding new questions
            addQuestionBtn.addEventListener('click', function() {
                addQuestion();
            });

            // Event listener for form submission to show pop-up (if desired)
            quizForm.addEventListener('submit', function(event) {
                // If needed, you can do additional client-side validation here
                // alert('Quiz has been created successfully! Congratulations!');
            });
        });
    </script>
</body>

</html>