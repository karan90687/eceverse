<?php
session_start();
require 'db_connect.php';

// Initialize variables
$user_id = $_SESSION['user_id'] ?? null;
$selected_category = $_GET['category'] ?? 1;

$status = $_GET['status'] ?? 'all';
$search = $_GET['search'] ?? '';


$level = $_GET['level'] ?? 'all';
$difficulty = $_GET['difficulty'] ?? 'all';
$popularity = $_GET['popularity'] ?? 'all';

$page = max(1, $_GET['page'] ?? 1);
$perPage = 10;

// Get all categories
$categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);

// Get user progress stats
$progressStmt = $pdo->prepare("
    SELECT 
        COUNT(*) as total_problems,
        SUM(IF(up.is_solved = 1 AND up.user_id = ?, 1, 0)) as solved_problems
    FROM problems p
    LEFT JOIN user_progress up ON p.id = up.problem_id
");
$progressStmt->execute([$user_id]);
$progress = $progressStmt->fetch();
$progressPercent = $progress['total_problems'] > 0 ? round(($progress['solved_problems'] / $progress['total_problems']) * 100) : 0;

// Base query
$sql = "SELECT p.*, 
        (SELECT COUNT(*) FROM user_progress up WHERE up.problem_id = p.id AND up.user_id = ? AND up.is_solved = 1) as is_solved,
        GROUP_CONCAT(c.name SEPARATOR ' ') as topics
        FROM problems p
        LEFT JOIN problem_categories pc ON p.id = pc.problem_id
        LEFT JOIN categories c ON pc.category_id = c.id";

$params = [$user_id];

// Apply category filter if not "All Problems"
if ($selected_category != 1) {
    $sql .= " WHERE pc.category_id = ?";
    $params[] = $selected_category;
} else {
    // For All Problems, only include Analog (id=2) and Digital (id=3)
    $sql .= " WHERE pc.category_id IN (2, 3, 5, 6, 7)";
}


// Apply popularity filter (popular / nonpopular)
if ($popularity !== 'all' && in_array($popularity, ['popular','nonpopular'])) {
    $sql .= (strpos($sql, 'WHERE') !== false ? ' AND' : ' WHERE');
    $sql .= " p.popularity = ?";
    $params[] = $popularity;
}

// Apply level filter (Practice / Interview)
if ($level !== 'all') {
    $sql .= (strpos($sql, 'WHERE') !== false ? ' AND' : ' WHERE');
    $sql .= " p.level = ?";
    $params[] = $level;
}

// Apply difficulty filter (Easy / Medium / Hard)
if ($difficulty !== 'all' && in_array(ucfirst($difficulty), ['Easy', 'Medium', 'Hard'])) {
    $sql .= (strpos($sql, 'WHERE') !== false ? ' AND' : ' WHERE');
    $sql .= " p.difficulty = ?";
    $params[] = ucfirst($difficulty);
}




// Apply status filter (solved / unsolved)
if ($status != 'all' && $user_id) {
    $sql .= (strpos($sql, 'WHERE') !== false ? ' AND' : ' WHERE');
    if ($status == 'solved') {
        $sql .= " EXISTS (SELECT 1 FROM user_progress up WHERE up.problem_id = p.id AND up.user_id = ? AND up.is_solved = 1)";
    } else {
        $sql .= " NOT EXISTS (SELECT 1 FROM user_progress up WHERE up.problem_id = p.id AND up.user_id = ? AND up.is_solved = 1)";
    }
    $params[] = $user_id;
}

// Apply search filter
if (!empty($search)) {
    $sql .= (strpos($sql, 'WHERE') !== false ? ' AND' : ' WHERE');
    $sql .= "(p.title LIKE ? OR p.popularity LIKE ? OR p.level LIKE ? OR p.difficulty LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}


// // Apply tag filter (optional)
// $selected_tag = $_GET['tag'] ?? 'all';

// if ($selected_tag !== 'all') {
//     $sql .= (strpos($sql, 'WHERE') !== false ? ' AND' : ' WHERE');
//     $sql .= " FIND_IN_SET(?, p.tags)";
//     $params[] = $selected_tag;
// }


// Apply saved filter (saved problems)
if (isset($_GET['list']) && $_GET['list'] === 'saved' && $user_id) {
    $sql .= (strpos($sql, 'WHERE') !== false ? ' AND' : ' WHERE');
    $sql .= " EXISTS (SELECT 1 FROM user_progress up2 WHERE up2.problem_id = p.id AND up2.user_id = ? AND up2.is_saved = 1)";
    $params[] = $user_id;
}

// Group by problem ID to avoid duplicates
$sql .= " GROUP BY p.id";

// Pagination
$countSql = "SELECT COUNT(*) as total FROM ($sql) as temp";
$countStmt = $pdo->prepare($countSql);
$countStmt->execute($params);
$totalProblems = $countStmt->fetch()['total'];
$totalPages = ceil($totalProblems / $perPage);

// Apply LIMIT for current page
$sql .= " LIMIT " . (($page - 1) * $perPage) . ", $perPage";

// Final query execution
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$problems = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Function for pagination links
function buildPaginationLink($page) {
    $params = $_GET;
    $params['page'] = $page;
    return 'problems.php?' . http_build_query($params);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Problem Set | LeetCore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #1a1a1a;
            --primary-light: #262626;
            --primary-dark: #0d0d0d;
            --success: #28a745;
            --warning: #ffc107;
            --danger: #dc3545;
            --easy: #00b8a3;
            --medium: #ffc01e;
            --hard: #ff375f;
            --light-gray: #f5f7fa;
            --medium-gray: #e1e5ee;
            --dark-gray: #6b7280;
            --sidebar-width: 280px;
            --header-height: 50px;
            --lc-primary: #ffa116;
            --lc-text-primary: #263238;
            --lc-text-secondary: #6c757d;
            --lc-bg-primary: #ffffff;
            --lc-bg-secondary: #f8f9fa;
            --lc-bg-tertiary: #f1f1f1;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: var(--lc-bg-secondary);
            color: var(--lc-text-primary);
            min-height: 100vh;
            margin: 0;
            padding-top: var(--header-height);
        }
        
        .navbar {
            background-color: var(--lc-bg-primary);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            height: var(--header-height);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }
        
        .navbar-brand {
            font-weight: 600;
            color: var(--lc-primary);
            font-size: 1.25rem;
            display: flex;
            align-items: center;
        }
        
        .navbar-brand img {
            height: 24px;
            margin-right: 8px;
        }
        
        .nav-link {
            font-weight: 500;
            color: var(--lc-text-secondary);
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
        
        .nav-link.active {
            color: var(--lc-text-primary);
        }
        
        .main-container {
            display: flex;
            min-height: calc(100vh - var(--header-height));
        }
        
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--lc-bg-primary);
            border-right: 1px solid var(--medium-gray);
            padding: 1rem 0;
            overflow-y: auto;
            position: fixed;
            top: var(--header-height);
            bottom: 0;
            left: 0;
        }
        
        .content-area {
            flex: 1;
            padding: 1.5rem;
            margin-left: var(--sidebar-width);
            max-width: 1200px;
        }
        
        .domain-header {
            font-weight: 600;
            color: var(--lc-text-primary);
            margin-bottom: 0.5rem;
            padding: 0.5rem 1.5rem;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .topic-item {
            padding: 0.5rem 1.5rem;
            margin-bottom: 0.25rem;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.9rem;
            color: var(--lc-text-secondary);
            display: flex;
            align-items: center;
        }
        
        .topic-item:hover {
            background-color: var(--lc-bg-tertiary);
        }
        
        .topic-item.active {
            background-color: rgba(255, 161, 22, 0.1);
            color: var(--lc-primary);
            font-weight: 500;
            border-left: 3px solid var(--lc-primary);
        }
        
        .topic-item i {
            margin-right: 0.75rem;
            width: 16px;
            text-align: center;
        }
        
        .filter-container {
            background-color: var(--lc-bg-primary);
            border-radius: 6px;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }
        
        .filter-group {
            margin-bottom: 0.5rem;
        }
        
        .filter-group-title {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--lc-text-primary);
            font-size: 0.9rem;
        }
        
        .filter-btn {
            background-color: var(--lc-bg-primary);
            border: 1px solid var(--medium-gray);
            color: var(--lc-text-secondary);
            padding: 0.25rem 0.75rem;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
            border-radius: 4px;
            transition: all 0.2s;
            font-size: 0.85rem;
        }
        
        .filter-btn:hover, .filter-btn.active {
            background-color: var(--lc-primary);
            color: white;
            border-color: var(--lc-primary);
        }
        
        .problem-table {
            background-color: var(--lc-bg-primary);
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }
        
        .problem-table th {
            background-color: var(--lc-bg-tertiary);
            font-weight: 600;
            padding: 0.75rem 1rem;
            border: none;
            font-size: 0.85rem;
            color: var(--lc-text-secondary);
        }
        
        .problem-table td {
            padding: 0.75rem 1rem;
            vertical-align: middle;
            border-top: 1px solid var(--medium-gray);
            font-size: 0.9rem;
        }
        
        .problem-title {
            color: var(--lc-text-primary);
            font-weight: 500;
            text-decoration: none;
            transition: color 0.2s;
        }
        
        .problem-title:hover {
            color: var(--lc-primary);
        }
        
        .difficulty-easy {
            color: var(--easy);
            font-weight: 500;
        }
        
        .difficulty-medium {
            color: var(--medium);
            font-weight: 500;
        }
        
        .difficulty-hard {
            color: var(--hard);
            font-weight: 500;
        }
        
        .solved-icon {
            color: var(--success);
        }
        
        .unsolved-icon {
            color: var(--medium-gray);
        }
        
        .category-tag {
            background-color: var(--lc-bg-tertiary);
            color: var(--lc-text-secondary);
            padding: 0.2rem 0.6rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 400;
        }
        
        .search-input {
            border-radius: 4px;
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--medium-gray);
            transition: all 0.2s;
            width: 100%;
            font-size: 0.9rem;
        }
        
        .search-input:focus {
            border-color: var(--lc-primary);
            box-shadow: 0 0 0 2px rgba(255, 161, 22, 0.2);
            outline: none;
        }
        
        .progress-container {
            background-color: var(--lc-bg-primary);
            border-radius: 6px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .progress-text {
            font-weight: 500;
            font-size: 0.9rem;
            color: var(--lc-text-secondary);
        }
        
        .progress-text strong {
            color: var(--lc-text-primary);
        }
        
        .progress-bar {
            height: 6px;
            border-radius: 3px;
            background-color: var(--lc-bg-tertiary);
            overflow: hidden;
            width: 200px;
        }
        
        .progress-fill {
            height: 100%;
            background-color: var(--lc-primary);
            width: <?= $progressPercent ?>%;
        }
        
        .dropdown-toggle::after {
            margin-left: 0.5rem;
        }
        
        .dropdown-menu {
            font-size: 0.9rem;
            border: 1px solid rgba(0, 0, 0, 0.05);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        .dropdown-item {
            padding: 0.5rem 1rem;
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        .premium-lock {
            color: var(--warning);
            margin-left: 0.5rem;
        }
        
        @media (max-width: 992px) {
            .main-container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                position: static;
                border-right: none;
                border-bottom: 1px solid var(--medium-gray);
                height: auto;
                max-height: 300px;
            }
            
            .content-area {
                margin-left: 0;
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="https://leetcode.com/static/images/LeetCode_logo_rvs.png" alt="LeetCore Logo">
                LeetCore
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Problems</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Discuss</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contest</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i> <?= $user_id ? 'User'.$user_id : 'Guest' ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <?php if($user_id): ?>
                                <li><a class="dropdown-item" href="#">Profile</a></li>
                                <li><a class="dropdown-item" href="#">Account</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                            <?php else: ?>
                                <li><a class="dropdown-item" href="login.php">Login</a></li>
                                <li><a class="dropdown-item" href="register.php">Register</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-container">
        <!-- Sidebar - Categories -->
        <div class="sidebar">
            <h5 class="domain-header">Categories</h5>
            
            <div class="topic-item <?= $selected_category == 1 ? 'active' : '' ?>">
                <a href="problems.php?category=1" style="text-decoration: none; color: inherit;">
                    <i class="fas fa-list"></i> All Problems
                </a>
            </div>
            
            <?php foreach ($categories as $cat): ?>
    <?php if ($cat['id'] != 1): ?>
        <div class="topic-item <?= $selected_category == $cat['id'] ? 'active' : '' ?>">
            <a href="problems.php?category=<?= $cat['id'] ?>" style="text-decoration: none; color: inherit;">
                <i class="fas <?= $cat['icon'] ?>"></i> <?= htmlspecialchars($cat['name']) ?>
            </a>
        </div>
    <?php endif; ?>
<?php endforeach; ?>

            
            <h5 class="domain-header">Study Plan</h5>
<div class="topic-item <?= ($_GET['list'] ?? '') == 'saved' ? 'active' : '' ?>">
    <a href="problems.php?list=saved&category=<?= $selected_category ?>" style="text-decoration: none; color: inherit;">
        <i class="fas fa-bookmark"></i> Saved Problems
    </a>
</div>

            
        </div>

        <!-- Content Area -->
        <div class="content-area">
            <!-- Progress Summary -->
            <div class="progress-container">
                <div class="progress-text">
                    <i class="fas fa-check-circle me-1 text-success"></i>
                    Solved: <strong><?= $progress['solved_problems'] ?? 0 ?></strong> / 
                    <strong><?= $progress['total_problems'] ?? 0 ?></strong> 
                    (<strong><?= $progressPercent ?>%</strong>)
                </div>
                <div class="progress-bar">
                    <div class="progress-fill"></div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="filter-container">
                <form id="filterForm" method="get" action="problems.php">
                    <input type="hidden" name="category" value="<?= $selected_category ?>">


                    <div class="row mt-1">
                        <div class="col-md-6">
                            <div class="filter-group">
                               <div class="filter-group-title">Lists</div>
                               <div>
                                    <button type="button" class="filter-btn <?= ($_GET['level'] ?? 'all') === 'all' ? 'active' : '' ?>" data-value="all">All</button>
                                    <button type="button" class="filter-btn <?= ($_GET['level'] ?? '') === 'Practice' ? 'active' : '' ?>" data-value="Practice">Practice</button>
                                    <button type="button" class="filter-btn <?= ($_GET['level'] ?? '') === 'Interview' ? 'active' : '' ?>" data-value="Interview">Interview</button>
                                    <input type="hidden" name="level" id="levelInput" value="<?= htmlspecialchars($_GET['level'] ?? 'all') ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="filter-group">
                               <div class="filter-group-title">Difficulty</div>
                              <div>
                                    <button type="button" class="filter-btn <?= $difficulty === 'all' ? 'active' : '' ?>" data-value="all">All</button>
                                    <button type="button" class="filter-btn <?= $difficulty === 'easy' ? 'active' : '' ?>" data-value="easy">Easy</button>
                                    <button type="button" class="filter-btn <?= $difficulty === 'medium' ? 'active' : '' ?>" data-value="medium">Medium</button>
                                    <button type="button" class="filter-btn <?= $difficulty === 'hard' ? 'active' : '' ?>" data-value="hard">Hard</button>
                                    <input type="hidden" name="difficulty" id="difficultyInput" value="<?= $difficulty ?>">
                              </div>
                            </div>
                        </div>
                    </div>
                    

                    <div class="row mt-2">
                        <div class="col-md-6">
                            <div class="filter-group">
                                <div class="filter-group-title">Status</div>
                                <div>
                                    <button type="button" class="filter-btn <?= $status === 'all' ? 'active' : '' ?>" data-value="all">All</button>
                                    <button type="button" class="filter-btn <?= $status === 'solved' ? 'active' : '' ?>" data-value="solved">Solved</button>
                                    <button type="button" class="filter-btn <?= $status === 'unsolved' ? 'active' : '' ?>" data-value="unsolved">Unsolved</button>
                                    <input type="hidden" name="status" id="statusInput" value="<?= $status ?>">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="filter-group">
                               <div class="filter-group-title">popularity</div>
                               <div>
                                    <button type="button" class="filter-btn <?= ($_GET['popularity'] ?? '') === 'popular' ? 'active' : '' ?>" data-value="popular">Popular</button>
                                    <button type="button" class="filter-btn <?= ($_GET['popularity'] ?? '') === 'nonpopular' ? 'active' : '' ?>" data-value="nonpopular">Nonpopular</button>
                                    <input type="hidden" name="popularity" id="popularityInput" value="<?= htmlspecialchars($_GET['popularity'] ?? 'all') ?>">
                                </div>
                            </div>
                        </div>


                    </div>
                    
                    <div class="filter-group mt-2">
                        <div class="filter-group-title">Search</div>
                        <input type="text" name="search" class="search-input" placeholder="Search questions..." value="<?= htmlspecialchars($search) ?>">
                    </div>
                </form>
            </div>

            <!-- Problem Table -->
            <div class="table-responsive">
                <table class="table problem-table">
                    <thead>
                        <tr>
                            <th width="5%">Status</th>
                            <th width="45%">Title</th>
                            <th width="30%">popularity</th>
                            <th width="30%">level</th>
                            <th width="20%">Difficulty</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($problems as $problem): ?>
                    <tr>
                    <td>
                         <i class="fas <?= $problem['is_solved'] ? 'fa-check-circle solved-icon' : 'fa-times-circle unsolved-icon' ?>"></i>
                    </td>
  <td>
    <a href="#" class="problem-title"><?= htmlspecialchars($problem['title']) ?></a>
  </td>
  <td>
    <span class="category-tag"><?= $problem['popularity'] ?></span>
  </td>
  <td>
    <span class="category-tag"><?= $problem['level'] ?></span>
  </td>
  <td>
    <span class="difficulty-<?= strtolower($problem['difficulty']) ?>"><?= $problem['difficulty'] ?></span>
  </td>
</tr>
<?php endforeach; ?>

                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <nav aria-label="Problem pagination" class="mt-3">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= buildPaginationLink($page - 1) ?>" tabindex="-1">Previous</a>
                    </li>
                    
                    <?php for ($i = 1; $i <= min($totalPages, 5); $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link" href="<?= buildPaginationLink($i) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    
                    <?php if ($totalPages > 5): ?>
                        <li class="page-item disabled"><a class="page-link" href="#">...</a></li>
                        <li class="page-item">
                            <a class="page-link" href="<?= buildPaginationLink($totalPages) ?>"><?= $totalPages ?></a>
                        </li>
                    <?php endif; ?>
                    
                    <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= buildPaginationLink($page + 1) ?>">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Handle filter button clicks
            $('.filter-btn[data-value]').click(function() {
                const group = $(this).parent();
                group.find('.filter-btn').removeClass('active');
                $(this).addClass('active');
                
                // Update the corresponding hidden input
                const inputId = group.find('input[type="hidden"]').attr('id');

                const value = $(this).data('value');
                $('#' + inputId).val(value);
                
                // Reset to first page when filters change
                $('<input>').attr({
                    type: 'hidden',
                    name: 'page',
                    value: 1
                }).appendTo('#filterForm');
                
                $('#filterForm').submit();
            });

            // Handle search with debounce
            // Submit form only when Enter is pressed in the search input
$('.search-input').on('keydown', function(event) {
    if (event.key === 'Enter') {
        event.preventDefault(); // prevent form submission if needed
        $('<input>').attr({
            type: 'hidden',
            name: 'page',
            value: 1
        }).appendTo('#filterForm');
        $('#filterForm').submit();
    }
});


            // Premium lock tooltip
            $('.premium-lock').tooltip({
                trigger: 'hover',
                placement: 'right'
            });
        });
    </script>
</body>
</html>