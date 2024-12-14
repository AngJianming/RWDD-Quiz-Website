<?php
// Capture the 'level' parameter from the URL
$level = isset($_GET['level']) ? htmlspecialchars($_GET['level']) : 'Level';

// Initialize variables for error and success messages
$error = '';
$success = '';

// Assuming you have logic here to handle form submission and populate $levels and $questions
// For example:
// $levels = getLevelsFromDatabase();
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     // Validate and process the form data
//     // Set $error or $success accordingly
// }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Ranked Levels</title>
    <link rel="stylesheet" href="addranked.css">
    <!-- <?php //include '../Constants/Combine-admin.php'; 
            ?> -->
    <?php include '../Constants/Combine-admin.php'; ?>
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
                <input type="text" id="quiz_name" name="quiz_name" required value="<?php echo isset($_POST['quiz_name']) ? htmlspecialchars($_POST['quiz_name']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="quiz_description">Quiz Description:</label>
                <textarea id="quiz_description" name="quiz_description" rows="4" required><?php echo isset($_POST['quiz_description']) ? htmlspecialchars($_POST['quiz_description']) : ''; ?></textarea>
            </div>

            <div class="form-group">
                <label for="ranked_quiz_id">Quiz Level:</label>
                <select id="ranked_quiz_id" name="ranked_quiz_id" required>
                    <option value="">--Select Level--</option>
                    <?php foreach ($levels as $lvl): ?>
                        <option value="<?php echo htmlspecialchars($lvl['ranked_quiz_id']); ?>" <?php if (isset($_POST['ranked_quiz_id']) && $_POST['ranked_quiz_id'] == $lvl['ranked_quiz_id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($lvl['ranked_quiz_level'] . " - " . $lvl['ranked_quiz_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <h2>Questions</h2>
            <div id="questionsContainer">
                <?php
                // If form was submitted with errors, repopulate questions
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['questions'])) {
                    foreach ($_POST['questions'] as $index => $q) {
                ?>
                        <div class="question-block">
                            <h3>Question <?php echo $index + 1; ?></h3>
                            <div class="form-group">
                                <label>Question Text:</label>
                                <textarea name="questions[<?php echo $index; ?>][question_text]" rows="3" required><?php echo htmlspecialchars($q['question_text']); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Option A:</label>
                                <input type="text" name="questions[<?php echo $index; ?>][option_A]" required value="<?php echo htmlspecialchars($q['option_A']); ?>">
                            </div>
                            <div class="form-group">
                                <label>Option B:</label>
                                <input type="text" name="questions[<?php echo $index; ?>][option_B]" required value="<?php echo htmlspecialchars($q['option_B']); ?>">
                            </div>
                            <div class="form-group">
                                <label>Option C:</label>
                                <input type="text" name="questions[<?php echo $index; ?>][option_C]" required value="<?php echo htmlspecialchars($q['option_C']); ?>">
                            </div>
                            <div class="form-group">
                                <label>Option D:</label>
                                <input type="text" name="questions[<?php echo $index; ?>][option_D]" required value="<?php echo htmlspecialchars($q['option_D']); ?>">
                            </div>
                            <div class="form-group">
                                <label>Correct Option:</label>
                                <select name="questions[<?php echo $index; ?>][correct_option]" required>
                                    <option value="">--Select Correct Option--</option>
                                    <option value="A" <?php if (isset($q['correct_option']) && $q['correct_option'] === 'A') echo 'selected'; ?>>A</option>
                                    <option value="B" <?php if (isset($q['correct_option']) && $q['correct_option'] === 'B') echo 'selected'; ?>>B</option>
                                    <option value="C" <?php if (isset($q['correct_option']) && $q['correct_option'] === 'C') echo 'selected'; ?>>C</option>
                                    <option value="D" <?php if (isset($q['correct_option']) && $q['correct_option'] === 'D') echo 'selected'; ?>>D</option>
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

            // Event listener for form submission to show pop-up
            quizForm.addEventListener('submit', function(event) {
                // Show the pop-up
                alert('Quiz has been created successfully! Congratulations!');
                // The form will submit naturally after the alert
            });
        });
    </script>
</body>

</html>