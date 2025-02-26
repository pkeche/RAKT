<?php
    require_once __DIR__ . '/../includes/session.inc.php';
    include __DIR__ . '/../includes/dbh.inc.php';

    function login_template(string $name)
    {
        echo
        '
        <style>
            .form-group .user-box {
                position: relative;
                margin: 30px 0px;
            }
            .form-group .user-box input {
                width: 100%;
                padding: 10px 0;
                font-size: 16px;
                color: #000;
                border: none;
                border-bottom: 1px solid #000;
                background: transparent;
                border-radius: 0; 
                outline: none;
            }
            .form-group .user-box label {
                position: absolute;
                top: 0;
                left: 0;
                padding: 10px 0;
                font-size: 16px;
                color: #000;
                pointer-events: none;
                transition: .5s;
            }
            .form-group .user-box input:focus ~ label,
            .form-group .user-box input:valid ~ label {
                top: -30px;
                left: 0;
                color: #000;
                font-size: 12px;
            }
            .form-group .user-box input:focus ~ label,
            .form-group .user-box input:valid ~ label,
            .form-group .user-box input:active ~ label {
                color: #000;
                font-size: 12px;
                top: -30px;
                left: 0;
            }
        </style>
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="form-container" style="border: 2px solid #d9534f;border-radius:10px;">
                    <div class="d-flex justify-content-between">
                        <h2 class="text-center">' . $name . '</h2>
                    </div>
                    <form action="login.inc.php" method="post">
                        <div class="form-group">
                            <div class="user-box">
                                <input type="text" class="form-control" name="username" required>
                                <label>Username</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="user-box">
                                <input type="password" class="form-control" name="pwd" required>
                                <label>Password</label>
                            </div>
                        </div>';
                        echo
                        '<button class="btn" type="submit" style="color:#fff;background-color:#0047ab;border:none;">Login</button>
                    </form>';
                    if($name == "Admin Login") echo '<p class="mt-3">Forgot login Details? <a href="register.php">Reset to Default</a></p>';
                    else echo '<p class="mt-3">New User? <a href="register.php">Register here</a></p>';
                echo '</div>
            </div>
        </div>
        ';
    }

    function register_template(string $name)
    {if ($name !== "Admin Register")
        {echo
        '
        <style>
            .form-group .user-box {
                position: relative;
                margin: 30px 0px;
            }
            .form-group .user-box input {
                width: 100%;
                padding: 10px 0;
                font-size: 16px;
                color: #000;
                border: none;
                border-bottom: 1px solid #000;
                background: transparent;
                border-radius: 0; 
                outline: none;
            }
            .form-group .user-box label {
                position: absolute;
                top: 0;
                left: 0;
                padding: 10px 0;
                font-size: 16px;
                color: #000;
                pointer-events: none;
                transition: .5s;
            }
            .form-group .user-box input:focus ~ label,
            .form-group .user-box input:valid ~ label {
                top: -30px;
                left: 0;
                color: #000;
                font-size: 12px;
            }
            .form-group .user-box input:focus ~ label,
            .form-group .user-box input:valid ~ label,
            .form-group .user-box input:active ~ label {
                color: #000;
                font-size: 12px;
                top: -30px;
                left: 0;
            }
        </style>
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="form-container" style="border: 2px solid #DC3545;border-radius:10px;">
                    <div class="d-flex justify-content-between">
                        <h2 class="text-center">' . $name . '</h2>
                    </div>
                    <form action="register.inc.php" method="post">
                        <div class="form-group">
                            <div class="user-box">
                                <input type="text" class="form-control" name="name" required>
                                <label>Name</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="user-box">
                                <input type="email" class="form-control" name="email" required>
                                <label>Email</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="user-box">
                                <input type="pincode" class="form-control" name="pincode" required>
                                <label>Pincode</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="user-box">
                                <input type="text" class="form-control" name="username" required>
                                <label>Username</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="user-box">
                                <input type="password" class="form-control" name="pwd" required>
                                <label>Password</label>
                            </div>
                        </div>';

                        {
                            echo
                            '<div class="form-group">
                                <div class="user-box">
                                    <select class="form-control" name="blood" required>
                                        <option value="">Select your blood group</option>
                                        <option value="A+">A+</option>
                                        <option value="A-">A-</option>
                                        <option value="B+">B+</option>
                                        <option value="B-">B-</option>
                                        <option value="O+">O+</option>
                                        <option value="O-">O-</option>
                                        <option value="AB+">AB+</option>
                                        <option value="AB-">AB-</option>
                                    </select>
                                </div>
                            </div>';
                        }

                        echo
                        '<!-- Add more registration fields as needed -->
                        <button class="btn" type="submit" style="color:#fff;background-color:#0047ab;border:none;">Register</button>
                    </form>';
                    echo '<p class="mt-3">Already have an account? <a href="login.php">Login here</a></p>';
                echo '</div>
            </div>
        </div>
        ';}
    if ($name == "Admin Register")
        {echo
        '
        <style>
            .form-group .user-box {
                position: relative;
                margin: 30px 0px;
            }
            .form-group .user-box input {
                width: 100%;
                padding: 10px 0;
                font-size: 16px;
                color: #000;
                border: none;
                border-bottom: 1px solid #000;
                background: transparent;
                border-radius: 0; 
                outline: none;
            }
            .form-group .user-box label {
                position: absolute;
                top: 0;
                left: 0;
                padding: 10px 0;
                font-size: 16px;
                color: #000;
                pointer-events: none;
                transition: .5s;
            }
            .form-group .user-box input:focus ~ label,
            .form-group .user-box input:valid ~ label {
                top: -30px;
                left: 0;
                color: #000;
                font-size: 12px;
            }
            .form-group .user-box input:focus ~ label,
            .form-group .user-box input:valid ~ label,
            .form-group .user-box input:active ~ label {
                color: #000;
                font-size: 12px;
                top: -30px;
                left: 0;
            }
        </style>
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="form-container" style="border: 2px solid #DC3545;border-radius:10px;">
                    <div class="d-flex justify-content-between">
                        <h2 class="text-center">' ."Admin Password Reset". '</h2>
                    </div>
                    <form action="register.inc.php" method="post">
                    <br><h5> <p> "Will reset the Admin Credentials to the Default State"</h5></p><br>';
                        echo
                        '<!-- Add more registration fields as needed -->
                        <button class="btn"  type="submit" style="color:#fff;background-color:#0047ab;border:none;">Reset</button>
                    </form>';
                    echo '<p class="mt-3">Already have an account? <a href="login.php">Login here</a></p>';
                echo '</div>
            </div>
        </div>
        ';}
    
    }

    function profile_template(array $row, string $role)
{
    // Database connection
    global $pdo; 

    // Get pincode from the row
    $pincode = $row['pincode'];
    $dis = "";
    $state = "";

    // Fetch city and state using pincode
    $sql = "SELECT district1, state1 FROM info WHERE pincode = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$pincode]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $state = $result['state1'];
        $dis = $result['district1'];
    }

    // Continue processing...

    if ($role !== 'Admin'){
        echo '
        <script>
            function confirmDelete(event) {
                if (!confirm("Are you sure you want to delete this profile? This action cannot be undone.")) {
                    event.preventDefault(); // Prevent form submission if canceled
                }
            }
            
            // Show success alert if deletion was successful
            window.onload = function() {
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.has("deleted")) {
                    alert("Profile deleted successfully!");
                }
            };
        </script>
    
        <div class="container mb-5">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="form-container p-3" style="border: 2px solid #d9534f; border-radius: 10px;">
                        <h2 class="text-center">Profile</h2>
                        <form action="update_delete.php" method="post">
    
                            <div class="form-group row">
                                <label for="role" class="col-sm-3 col-form-label">Role</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="role" value="'.$role.'" readonly>
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <label for="name" class="col-sm-3 col-form-label">Name</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="name" value="'.$row['name'].'">
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <label for="email" class="col-sm-3 col-form-label">Email</label>
                                <div class="col-sm-9">
                                    <input type="email" class="form-control" name="email" value="'.$row['email'].'">
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <label for="pincode" class="col-sm-3 col-form-label">Pincode</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="pincode" value="'.$row['pincode'].'" >
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <label for="district" class="col-sm-3 col-form-label">District</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="district" value="'.$dis.'" readonly>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="state" class="col-sm-3 col-form-label">State</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="state" value="'.$state.'" readonly>
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <label for="username" class="col-sm-3 col-form-label">Username</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="username" value="'.$row['username'].'"readonly>
                                </div>
                            </div>';

                            if (isset($row['blood'])) {
                                echo '<div class="form-group row">
                                        <label for="bloodgroup" class="col-sm-3 col-form-label">Blood Group</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="blood" value="'.$row['blood'].'" readonly>
                                        </div>
                                    </div>';
                            }

                            echo '<div class="form-group row">
                                <label for="password" class="col-sm-3 col-form-label">Password</label>
                                <div class="col-sm-9">
                                    <input type="password" class="form-control" name="password" placeholder="Enter new password" oninput="document.getElementById(\'password_updated\').value = this.value ? \'true\' : \'false\'">
                                </div>
                            </div>';

                            echo '<div class="text-center">
                                <button type="submit" name="update" class="btn" style="color:#fff;background-color:#107dac;">Update</button>
                                <button type="submit" name="delete" class="btn" style="color:#fff;background-color:#a91b0d;" onclick="confirmDelete(event)">Delete</button>
                            </div>
    
                            <input type="hidden" id="password_updated" name="password_updated" value="false">
                        </form>
                    </div>
                </div>
            </div>
        </div>';
    }
    else{
        echo '
        <script>
            function confirmDelete(event) {
                if (!confirm("Are you sure you want to delete this profile? This action cannot be undone.")) {
                    event.preventDefault(); // Prevent form submission if canceled
                }
            }
            
            // Show success alert if deletion was successful
            window.onload = function() {
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.has("deleted")) {
                    alert("Profile deleted successfully!");
                }
            };
        </script>
    
        <div class="container mb-5">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="form-container p-3" style="border: 2px solid #d9534f; border-radius: 10px;">
                        <h2 class="text-center">Profile</h2>
                        <form action="update_delete.php" method="post">

                            <div class="form-group row">
                                <label for="role" class="col-sm-3 col-form-label">Role</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="role" value="'.$role.'" readonly>
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <label for="name" class="col-sm-3 col-form-label">Name</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="name" value="'.$row['name'].'">
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <label for="email" class="col-sm-3 col-form-label">Email</label>
                                <div class="col-sm-9">
                                    <input type="email" class="form-control" name="email" value="'.$row['email'].'">
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <label for="pincode" class="col-sm-3 col-form-label">Pincode</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="pincode" value="'.$row['pincode'].'" >
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <label for="district" class="col-sm-3 col-form-label">District</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="district" value="'.$dis.'" readonly>
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <label for="state" class="col-sm-3 col-form-label">State</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="state" value="'.$state.'" readonly>
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <label for="username" class="col-sm-3 col-form-label">Username</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="username" value="'.$row['username'].'"readonly>
                                </div>
                            </div>';

                            if (isset($row['blood'])) {
                                echo '<div class="form-group row">
                                        <label for="bloodgroup" class="col-sm-3 col-form-label">Blood Group</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="blood" value="'.$row['blood'].'" readonly>
                                        </div>
                                    </div>';
                            }

                            echo '<div class="form-group row">
                                <label for="password" class="col-sm-3 col-form-label">Password</label>
                                <div class="col-sm-9">
                                    <input type="password" class="form-control" name="password" placeholder="Enter new password" oninput="document.getElementById(\'password_updated\').value = this.value ? \'true\' : \'false\'">
                                </div>
                            </div>';

                            echo '<div class="text-center">
                                <button type="submit" name="update" class="btn" style="color:#fff;background-color:#107dac;">Update</button>
                            </div>
    
                            <input type="hidden" id="password_updated" name="password_updated" value="false">
                        </form>
                    </div>
                </div>
            </div>
        </div>';
    }
}

    function donate_request_template(string $path, string $name, string $name1, string $name2, string $name3) {
    echo '
        <style>
            .form-group { display: flex; align-items: center; margin-bottom: 15px; }
            .form-group label { width: 10ch; font-weight: normal; text-align: right; margin-right: 10px; } /* Fixed label width */
            .form-group input, .form-group select { flex: 1; padding: 8px; border: 1px solid #ccc; border-radius: 5px; height: 38px; }
            .pincode-container { display: flex; gap: 8px; flex: 1; }
            .pincode-container input { width: 50%; } /* Half input size */
            .pincode-container button { width: 50%; background: #1abc9c; color: white; border: none; cursor: pointer; height: 38px; border-radius: 5px; }
            .pincode-container button:hover { background: #16a085; }
        </style>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                document.getElementById("searchBtn").addEventListener("click", async function() {
                    let pincode = document.getElementById("pincode").value.trim();
                    if (pincode === "") {
                        alert("Please enter a pincode.");
                        return;
                    }

                    try {
                        let response = await fetch("../includes/fetch_hospitals.php?pincode=" + pincode);
                        let hospitals = await response.json();

                        let dropdown = document.getElementById("hospitalDropdown");
                        dropdown.innerHTML = "";

                        if (hospitals.length > 0) {
                            hospitals.forEach(hospital => {
                                let option = document.createElement("option");
                                option.value = hospital;
                                option.textContent = hospital;
                                dropdown.appendChild(option);
                            });
                        } else {
                            let option = document.createElement("option");
                            option.value = "";
                            option.textContent = "No hospitals found";
                            dropdown.appendChild(option);
                        }
                    } catch (error) {
                        console.error("Error fetching hospitals:", error);
                        alert("Failed to fetch hospital data.");
                    }
                });
            });
        </script>

        <div class="container">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="form-container p-3" style="border: 2px solid #1abc9c; border-radius:10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
                        <h2 class="text-center">' . htmlspecialchars($name) . '</h2>
                        <br></br>
                        <form action="' . htmlspecialchars($path) . '" method="post">
                            
                            <!-- Reason Input -->
                            <div class="form-group">
                                <label for="' . htmlspecialchars($name2) . '">' . htmlspecialchars($name1) . '</label>
                                <input type="text" id="' . htmlspecialchars($name2) . '" name="' . htmlspecialchars($name2) . '" required>
                            </div>

                            <!-- Pincode + Search Button -->
                            <div class="form-group">
                                <label for="pincode">Pincode</label>
                                <div class="pincode-container">
                                    <input type="number" id="pincode" name="pincode" placeholder="Enter Pincode" required>
                                    <button type="button" id="searchBtn">Search</button>
                                </div>
                            </div>

                            <!-- Units Input Field (Default: 1) -->
                            <div class="form-group">
                                <label for="units">Units</label>
                                <input type="number" id="units" name="unit" min="1" value="1" required>
                            </div>

                            <!-- Nearest Hospital Dropdown -->
                            <div class="form-group">
                                <label>Nearest Hospital</label>
                                <select id="hospitalDropdown" name="hospital1" required>
                                    <option value="" selected disabled>Select a hospital</option>
                                </select>
                            </div>

                            <!-- Submit Button -->
                            <div class="text-center">
                                <button type="submit" class="btn" style="color:#fff; background-color:#0047ab;">' . htmlspecialchars($name3) . '</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>';
}

    function history_template(array $row,string $name1,string $name2, string $name3) 
    {
        echo
        '
        <style>
        .transparent-bg {
            background: #d9534f;
            transition: background 0.3s, transform 0.3s;
            border-radius:16px;
            color:#fff;
        }
        
        .transparent-bg:hover {
            transform: scale(1.05); /* Scale up the card on hover */
        }                       
        </style>
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4"> 
            <div class="card" style="box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);border-radius:16px;">
                <div class="card-body transparent-bg">
                    <p class="card-text">'.$name1.' : ' . $row[$name2] . '</p>
                    <p class="card-text">Units : ' . $row['unit'] . '</p>
                    <p class="card-text">Hospital : '. $row[$name3] . '</p>
                    <b><p class="card-text">' . $row['status'] . '</p></b>
                </div>
            </div>
        </div>
        ';
    }

    function home_template(array $name)
    {
        $imagePath = ($name[0] === 'Donor') ? '../images/donor-dashboard.svg' : '../images/patient-dashboard.svg';
        echo 
        '
            <style>
                .transparent-bg {
                    background: transparent;
                    transition: background 0.3s, transform 0.3s;
                    border: 2px solid #d9534f;
                    border-radius: 16px;
                }

                .transparent-bg:hover {
                    transform: translateY(-5px);
                }

                .card-body a , .card-body a:hover {
                    color:#fff;
                    background-color:#0047ab;
                }
            </style>
            <div class="container mt-5">
                <h2 class="text-center mb-4">' . $name[0] . ' Dashboard</h2>
                <div class="row align-items-center mt-4">
                    <div class="col-lg-6 mb-4">
                        <img src='.$imagePath.' class="img-fluid d-none d-lg-block">
                    </div>
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="card" style="border: none; border-radius: 16px; background: transparent;">
                                    <div class="card-body transparent-bg">
                                        <h5 class="card-title">' . $name[1] . ' Blood</h5>
                                        <p class="card-text">' . $name[2] . '</p>
                                        <a href="?' . $name[3] . '_blood=1" class="btn">' . $name[1] . ' Blood</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <div class="card" style="border: none; border-radius: 16px; background: transparent;">
                                    <div class="card-body transparent-bg">
                                        <h5 class="card-title">' . $name[4] . ' History</h5>
                                        <p class="card-text">' . $name[5] . '</p>
                                        <a href="?' . $name[6] . '_history=1" class="btn">' . $name[4] . ' History</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        ';
    }
