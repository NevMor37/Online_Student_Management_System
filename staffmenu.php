<?php

?>
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">Online Student Management System</a>
                
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <?php echo $user_name?><i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>
                        </li>
                        <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="./../../logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
                <li><a href="./../../logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
            </ul>
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li class="sidebar-search">
                            <div class="input-group custom-search-form">
                                <input type="text" class="form-control" placeholder="Search...">
                                <span class="input-group-btn">
                                <button class="btn btn-default" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                            </div>
                            <!-- /input-group -->
                        </li>
                        <li>
                            <a href="index.php"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                        </li>
                        <li>
                            <a href="#User Management"><i class="fa  fa-users fa-fw"></i> User Management</a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="#Admin"><i class="fa fa-key fa-fw"></i>Admin</a>
                                    <ul class="nav nav-third-level">
                                        <li>
                                            <a href="./AdminOperations.php"><i class="fa fa-plus-circle fa-fw"></i>Add/Remove</a>
                                        </li>
                                        <li>
                                            <a href="./AdminDisable.php"><i class="fa fa-ban fa-fw"></i>Enable/Disable</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="#Faculty"><i class="fa fa-user fa-fw"></i>Faculty</a>
                                    <ul class="nav nav-third-level">
	                                    <li>
	                                    	<a href="./uploadFaculty.php"><i class="fa fa-download fa-fw"></i>Import</a>
										</li>
										<li>
	                                    	<a href="./UpdateFaculty.php"><i class="fa fa-edit fa-fw"></i>Add/Remove/Update</a>
										</li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="studentviewdashboard.php"><i class="fa fa-list-ul fa-fw"></i> Student</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="#Admissions"><i class="fa fa-tasks fa-fw"></i>Admissions</a>
                            <ul class="nav nav-third-level">
                                <li>
                                    <a href="#Applications"><i class="fa fa-list-ul fa-fw"></i>Applications</a>
                                    <ul class="nav nav-third-level">
                                        <li>
                                            <a href="./uploadStudent.php"><i class="fa fa-download fa-fw"></i>Import</a>
                                        </li>
                                        <li>
                                            <a href="./UpdateStudent.php"><i class="fa fa-edit fa-fw"></i>Add/Remove/Update</a>
                                        </li>
                                    </ul>
                                </li>
	                            <li>
	                            	<a href="./UpdateGAE"><i class="fa  fa-long-arrow-up fa-fw"></i>Update GAE</a>
	                            </li>
                                <li>
                                    <a href="#Faculty Evaluation"><i class="fa fa-list-ul fa-fw"></i>Faculty Evaluation Process</a>
                                    <ul class="nav nav-third-level">
                                        <li>
                                            <a href="./AssignGAE.php"><i class="fa fa-angle-double-right fa-fw"></i>Assign GAE</a>
                                        </li>
                                        <li>
                                            <a href="./RemindFaculty.php"><i class="fa fa-refresh fa-fw"></i>Check Review/remind</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="#Decision By DGS"><i class="fa fa-list-ul fa-fw"></i>Decision By DGS</a>
                                    <ul class="nav nav-third-level">
                                        <li>
                                            <a href="./evaluationDashboard.php"><i class="fa fa-usd fa-fw"></i>Evaluation  </a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="#Orientation RSVP"><i class="fa fa-list-ul fa-fw"></i>Orientation RSVP</a>
                                    <ul class="nav nav-third-level">
                                        <li>
                                            <a href="orientationviewdashboard.php"><i class="fa fa-edit fa-fw"></i>Orientation View</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="#Admission Report"><i class="fa fa-list-ul fa-fw"></i>Admission Report</a>
                                    <ul class="nav nav-third-level">
                                    </ul>
                                </li>

                            </ul>
                        </li>
                        <li>
                            <a href="#GTA Funding"><i class="fa  fa-tasks fa-fw"></i> GTA Funding</a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="#Course"><i class="fa fa-list-ul fa-fw"></i>Course</a>
                                    <ul class="nav nav-third-level">
                                        <li>
                                            <a href="DataImportviewdashboard.php"><i class="fa fa-download fa-fw"></i>Import</a>
                                        </li>
                                        <li>
                                            <a href="tacourseviewdashboard.php"><i class="fa fa-edit fa-fw"></i>Insert/Update/Delete</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="GTAApplicationadmindashboard.php"><i class="fa fa-usd fa-fw"></i>Applicant Information</a>
                                </li>
                                <li>
                                    <a href="taassignmentviewdashboard.php"><i class="fa fa-usd fa-fw"></i>Assignment</a>
                                </li>
                                <li>
                                    <a href="#TAEvaluation"><i class="fa fa-list-ul fa-fw"></i>TA Evaluation</a>
                                    <ul class="nav nav-third-level">
                                        <li>
                                            <a href="TAEvaluationGereratedashboard.php"><i class="fa fa-edit fa-fw"></i>TA Evaluation Generate</a>
                                        </li>
                                        <li>
                                            <a href="TAEvaluationdashboard.php"><i class="fa fa-edit fa-fw"></i>TA Evaluation</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="GTAApplicationadminreportdashboard.php"><i class="fa fa-bar-chart fa-fw"></i> GTA Report</a>
                                </li>
                                <li>
                                    <a href="GTAConfig.php"><i class="fa fa-edit fa-fw"></i> GTA Config</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#Student Advising"><i class="fa  fa-tasks fa-fw"></i> Student Advising</a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="./FoundationCourses.php"><i class="fa fa-list-ul fa-fw"></i> Foundation Course</a>
                                </li>
                                <li>
                                    <a href=""><i class="fa fa-list-ul fa-fw"></i> GSTEP</a>
                                </li>
                                <li>
                                    <a href=""><i class="fa fa-list-ul fa-fw"></i> Plan Of Study</a>
                                </li>
                                <li>
                                    <a href=""><i class="fa fa-list-ul fa-fw"></i> Transfer Credit</a>
                                </li>
                                <li>
                                    <a href="./QualifierforPHDviewdashboard.php"><i class="fa fa-list-ul fa-fw"></i> PHD Qualifier</a>
                                </li>
                                <li>
                                    <a href=""><i class="fa fa-list-ul fa-fw"></i> CPT</a>
                                </li>
                                <li>
                                    <a href=""><i class="fa fa-list-ul fa-fw"></i> RCL</a>
                                </li>
                                <li>
                                    <a href=""><i class="fa fa-list-ul fa-fw"></i> GRA In Campus</a>
                                </li>
                                <li>
                                    <a href=""><i class="fa fa-list-ul fa-fw"></i> Graduation Auditing</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#Report"><i class="fa  fa-tasks fa-fw"></i> Report</a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href=""><i class="fa fa-list-ul fa-fw"></i> Admission Report</a>
                                </li>
                                <li>
                                    <a href=""><i class="fa fa-list-ul fa-fw"></i> GTA Report</a>
                                </li>
                                <li>
                                    <a href=""><i class="fa fa-list-ul fa-fw"></i> Student Report</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#Basic Data Management"><i class="fa  fa-tasks fa-fw"></i> Basic Data Management</a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="campusviewdashboard.php"><i class="fa fa-list-ul fa-fw"></i> Campus</a>
                                </li>
                                <li>
                                    <a href="schoolcourseviewdashboard.php"><i class="fa fa-list-ul fa-fw"></i> School Course</a>
                                </li>
                                <li>
                                    <a href="termviewdashboard.php"><i class="fa fa-list-ul fa-fw"></i> Term</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>