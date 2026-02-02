# IT_ASSET_MANAGEMENT

Project: IT Equipment/Asset Lifecycle Management for INF BN 
Description: This README file has an overview of the project like - Technology used, Folder structure, and detailed steps of execution for the IT Equipment/Asset Lifecycle Management System for INF BN.

--------------------------------------------------
1️. Technology Used at Different Levels:
--------------------------------------------------
```text
Layer -----------------------   Technology Used
UI                              PHP
Database                        MySQL Server Apache (via XAMPP)
Hosting                         Localhost (XAMPP)
Reports                         Excel format
```

--------------------------------------------------
2. Folder Structure for all codes/scripts:
--------------------------------------------------
```text
it_asset_management/
├─ index.php                 # Landing/routing
├─ dashboard.php             # Admin/summary dashboard
├─ logout.php
├─ auth/
│  └─ login.php              # Authentication
├─ config/
│  └─ db.php                 # Database connection config
├─ admin/
│  ├─ equipment_master.php   # Procurement/master equipment data
│  └─ disposal.php           # Disposal workflows / BER decisions
├─ clerk/
│  └─ allocation.php         # Issue/allocation handling
├─ it_jco/
│  ├─ maintenance.php        # Maintenance & repair workflows
│  └─ warranty.php           # Warranty tracking
├─ user/
│  └─ my_equipment.php       # End-user equipment view
└─ reports/
   ├─ holding_state.php
   ├─ lifecycle_report.php
   ├─ maintenance_report.php
   ├─ disposal_report.php
   ├─ analytics.php
   └─ downloads.php
```
--------------------------------------------------
3. Life Cycle Stages 
--------------------------------------------------
```text
Stages ----------------------- Implemented Page
Procurement	                  equipment_master.php
Issue	                        clerk/allocation.php
Use	                        allocation table (Issued state)
Maintenance	                  it_jco/maintenance.php
Repair	                     it_jco/maintenance.php
BER	                        disposal decision logic
Disposal	                     admin/disposal.php
```
