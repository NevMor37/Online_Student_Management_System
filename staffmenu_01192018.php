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
                              <!--    <li>
                                    <a href="./uploadStudent.php"><i class="fa fa-upload fa-fw"></i>Student Information</a>
                                </li>
                                <li>
                                    <a href="./uploadFaculty.php"><i class="fa fa-upload fa-fw"></i>Faculty Information</a>
                                </li>
                                -->
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
                                    <a href="./AdmittedStudents.php"><i class="fa fa-check-square-o fa-fw"></i>Admitted students</a>
<!--                                     <ul class="nav nav-third-level"> -->
<!-- 	                                    <li> -->
<!-- 	                                    	<a href="./MasterStudents.php"><i class="fa  fa-graduation-cap fa-fw"></i>MS</a> -->
<!-- 										</li> -->
<!-- 										<li> -->
<!-- 	                                    	<a href="./PhdStudents.php"><i class="fa fa-lightbulb-o fa-fw"></i>PHD</a> -->
<!-- 										</li> -->
<!--                                     </ul> -->
                                </li>
                                <li>
                                    <a href="./CheckIn.php"><i class="fa fa-check-square-o fa-fw"></i>Students Check-In</a>
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
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="#Admissions"><i class="fa fa-tasks fa-fw"></i>Admissions</a>
                            <ul class="nav nav-third-level">
	                            <li>
	                            	<a href="./UpdateGAE"><i class="fa  fa-long-arrow-up fa-fw"></i>Update GAE</a>
	                            </li>
	                            <li>
	                            	<a href="./AssignGAE.php"><i class="fa fa-angle-double-right fa-fw"></i>Assign GAE</a>
	                            </li>
	                            <li>
	                            	<a href="./RemindFaculty.php"><i class="fa fa-refresh fa-fw"></i>Check Review/remind</a>
	                            </li>
	                            <li>
	                            	<a href="./Dept Funding"><i class="fa fa-usd fa-fw"></i>Dept funding request  /<i class="fa fa-sign-in fa-fw"></i>Adviser Assignment</a>
	                            </li>
                                <li>
                                    <a href="./evaluationDashboard.php"><i class="fa fa-usd fa-fw"></i>Evaluation  </a>
                                </li>
                                 <li>
                                    <a href="./FoundationCourses.php"><i class="fa fa-usd fa-fw"></i>Foundation</a>
                                </li>
                                
                            </ul>
                        </li>
<!--                         <li> -->
<!--                             <a href="./test.php"><i class="fa fa-edit fa-fw"></i>Student Evaluation</a> -->
<!--                         </li> -->
						<li>
                            <a href="./reports.php"><i class="fa fa-bar-chart"></i>Reports</a>
                        </li>
                        <li>
                            <a href="./ApplicationFiles.php"><i class="fa fa-file-pdf-o"></i>Applications</a>
                        </li>
                        <li>
                            <a href="#Funding"><i class="fa  fa-tasks fa-fw"></i> GTA Funding</a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="GTAApplicationadmindashboard.php"><i class="fa fa-usd fa-fw"></i>Applicant Information</a>
                                    <!-- <a href="summerposition.php"><i class="fa fa-dashboard fa-fw"></i>Summer Position</a> -->

                                </li>
                                <li>
                                    <a href="taassignmentviewdashboard.php"><i class="fa fa-usd fa-fw"></i>Assignment</a>
                                    <!-- <a href="summerposition.php"><i class="fa fa-dashboard fa-fw"></i>Summer Position</a> -->

                                </li>

                                <li>
                                    <a href="tacourseviewdashboard.php"><i class="fa fa-list-ul fa-fw"></i> Course List</a>
                                    <!-- <a href="summerposition.php"><i class="fa fa-dashboard fa-fw"></i>Summer Position</a> -->

                                </li>
                                <li>
                                    <a href="GTAApplicationadminreportdashboard.php"><i class="fa fa-list-ul fa-fw"></i> GTA Report</a>
                                    <!-- <a href="summerposition.php"><i class="fa fa-dashboard fa-fw"></i>Summer Position</a> -->

                                </li>
                                <li>
                                    <a href="GTAConfig.php"><i class="fa fa-list-ul fa-fw"></i> GTA Config</a>
                                    <!-- <a href="summerposition.php"><i class="fa fa-dashboard fa-fw"></i>Summer Position</a> -->

                                </li>
                                <li>
                                    <a href="TAEvaluationdashboard.php"><i class="fa fa-edit fa-fw"></i>TA Evaluation</a>
                                </li>
                                <li>
                                    <a href="TAEvaluationGereratedashboard.php"><i class="fa fa-edit fa-fw"></i>TA Evaluation Generate</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#Basic Data Management"><i class="fa  fa-tasks fa-fw"></i> Basic Data Management</a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="studentviewdashboard.php"><i class="fa fa-list-ul fa-fw"></i> Student List</a>
                                    <!-- <a href="summerposition.php"><i class="fa fa-dashboard fa-fw"></i>Summer Position</a> -->

                                </li>
                                <li>
                                    <a href="campusviewdashboard.php"><i class="fa fa-list-ul fa-fw"></i> Campus List</a>
                                    <!-- <a href="summerposition.php"><i class="fa fa-dashboard fa-fw"></i>Summer Position</a> -->

                                </li>
                                <li>
                                    <a href="schoolcourseviewdashboard.php"><i class="fa fa-list-ul fa-fw"></i> School Course List</a>
                                    <!-- <a href="summerposition.php"><i class="fa fa-dashboard fa-fw"></i>Summer Position</a> -->

                                </li>
                                <li>
                                    <a href="termviewdashboard.php"><i class="fa fa-list-ul fa-fw"></i> Term List</a>
                                    <!-- <a href="summerposition.php"><i class="fa fa-dashboard fa-fw"></i>Summer Position</a> -->

                                </li>
                                <li>
                                    <a href="DataImportviewdashboard.php"><i class="fa fa-list-ul fa-fw"></i> Course Import</a>
                                    <!-- <a href="summerposition.php"><i class="fa fa-dashboard fa-fw"></i>Summer Position</a> -->

                                </li>
                            </ul>
                        </li>
<!--                         <li> -->
<!--                             <a href="#"><i class="fa fa-bar-chart-o fa-fw"></i> Charts<span class="fa arrow"></span></a> -->
<!--                             <ul class="nav nav-second-level"> -->
<!--                                 <li> -->
<!--                                     <a href="flot.html">Flot Charts</a> -->
<!--                                 </li> -->
<!--                                 <li> -->
<!--                                     <a href="morris.html">Morris.js Charts</a> -->
<!--                                 </li> -->
<!--                             </ul> -->
                            <!-- /.nav-second-level -->
<!--                         </li> -->
<!--                         <li> -->
<!--                             <a href="tables.html"><i class="fa fa-table fa-fw"></i> Tables</a> -->
<!--                         </li> -->
                        
<!--                         <li> -->
<!--                             <a href="#"><i class="fa fa-wrench fa-fw"></i> UI Elements<span class="fa arrow"></span></a> -->
<!--                             <ul class="nav nav-second-level"> -->
<!--                                 <li> -->
<!--                                     <a href="panels-wells.html">Panels and Wells</a> -->
<!--                                 </li> -->
<!--                                 <li> -->
<!--                                     <a href="buttons.html">Buttons</a> -->
<!--                                 </li> -->
<!--                                 <li> -->
<!--                                     <a href="notifications.html">Notifications</a> -->
<!--                                 </li> -->
<!--                                 <li> -->
<!--                                     <a href="typography.html">Typography</a> -->
<!--                                 </li> -->
<!--                                 <li> -->
<!--                                     <a href="icons.html"> Icons</a> -->
<!--                                 </li> -->
<!--                                 <li> -->
<!--                                     <a href="grid.html">Grid</a> -->
<!--                                 </li> -->
<!--                             </ul> -->
                            <!-- /.nav-second-level -->
<!--                         </li> -->
<!--                         <li> -->
<!--                             <a href="#"><i class="fa fa-sitemap fa-fw"></i> Multi-Level Dropdown<span class="fa arrow"></span></a> -->
<!--                             <ul class="nav nav-second-level"> -->
<!--                                 <li> -->
<!--                                     <a href="#">Second Level Item</a> -->
<!--                                 </li> -->
<!--                                 <li> -->
<!--                                     <a href="#">Second Level Item</a> -->
<!--                                 </li> -->
<!--                                 <li> -->
<!--                                     <a href="#">Third Level <span class="fa arrow"></span></a> -->
<!--                                     <ul class="nav nav-third-level"> -->
<!--                                         <li> -->
<!--                                             <a href="#">Third Level Item</a> -->
<!--                                         </li> -->
<!--                                         <li> -->
<!--                                             <a href="#">Third Level Item</a> -->
<!--                                         </li> -->
<!--                                         <li> -->
<!--                                             <a href="#">Third Level Item</a> -->
<!--                                         </li> -->
<!--                                         <li> -->
<!--                                             <a href="#">Third Level Item</a> -->
<!--                                         </li> -->
<!--                                     </ul> -->
                                    <!-- /.nav-third-level -->
<!--                                 </li> -->
<!--                             </ul> -->
                            <!-- /.nav-second-level -->
<!--                         </li> -->
<!--                         <li> -->
<!--                             <a href="#"><i class="fa fa-files-o fa-fw"></i> Sample Pages<span class="fa arrow"></span></a> -->
<!--                             <ul class="nav nav-second-level"> -->
<!--                                 <li> -->
<!--                                     <a href="blank.html">Blank Page</a> -->
<!--                                 </li> -->
<!--                                 <li> -->
<!--                                     <a href="login.html">Login Page</a> -->
<!--                                 </li> -->
<!--                             </ul> -->
                            <!-- /.nav-second-level -->
<!--                         </li> -->
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>