# Database Migrations

This directory contains all database migrations for the SureScholarship system.

## Migration Organization

### Core Tables (Users & Authentication)
- `0001_01_01_000000_create_users_table.php` - Users table with roles
- `0001_01_01_000001_create_cache_table.php` - Cache table
- `0001_01_01_000002_create_jobs_table.php` - Jobs queue table
- `2024_03_20_000001_create_notifications_table.php` - Notifications

### Scholars
- `2024_01_01_000000_create_scholars_table.php` - Main scholars table
- `2025_07_27_042307_create_scholar_renewals_table.php` - Scholar renewals
- `2025_07_27_042308_create_renewal_documents_table.php` - Renewal documents
- `2025_07_27_070406_add_missing_columns_to_scholar_renewals_table.php` - Additional renewal columns
- `2025_07_28_065226_create_scholar_performance_table.php` - Scholar performance tracking
- `2025_07_28_083240_create_scholar_feedback_table.php` - Scholar feedback

### Applications
- `2025_07_19_163537_create_scholarships_table.php` - Scholarship programs
- `2025_07_19_163538_create_applications_table.php` - Applications table
- `2025_07_19_163539_create_requirements_table.php` - Application requirements
- `2025_07_19_163540_add_scholarship_id_to_applications_table.php` - Link applications to scholarships
- `2025_01_20_000000_add_grade_photo_to_applications_table.php` - Grade photo upload

### Disbursements
- `2025_09_20_024003_create_tbl_disbursement_batches_table.php` - Disbursement batches
- `2025_09_20_024118_create_tbl_disbursement_batch_students_table.php` - Batch students
- `2025_09_23_045713_add_release_status_to_disbursement_batch_students_table.php` - Release tracking
- `2025_09_23_134223_add_requested_amount_to_tbl_disbursement_batch_students_if_missing.php` - Amount field
- `2025_10_01_000001_enhance_tbl_disbursement_batches_table.php` - Enhanced batch features
- `2025_10_01_000002_enhance_tbl_disbursement_batch_students_table.php` - Enhanced student features
- `2025_10_02_000004_recreate_allocation_logs_table.php` - **MAIN** allocation logs (office_id included)

### Budget & Requests
- `2025_10_02_000001_create_budget_requests_table.php` - Budget requests
- `2025_09_23_143724_create_requests_table.php` - System requests
- `2025_09_23_150000_add_disbursement_batch_id_to_requests_table.php` - Link to disbursements

### Supporting Tables
- `2025_07_28_175202_create_institutions_table.php` - Educational institutions
- `2025_07_28_200000_create_barangays_table.php` - Barangays
- `2025_07_27_074127_create_system_settings_table.php` - System configuration
- `2025_01_15_000004_create_residence_data_table.php` - Residence data
- `2025_07_29_160121_add_siblings_and_parents_to_users_table.php` - Family info
- `2025_07_29_172116_add_sibling_names_to_users_table.php` - Sibling names (JSON)

### Data Cleanup
- `2025_09_19_160628_remove_eligibility_criteria_and_benefits_from_scholarship_programs_table.php` - Remove deprecated columns

## Deleted Migrations (Cleaned Up)

The following migrations were removed as they were redundant or superseded:

1. ~~`2025_07_29_171817_add_sibling_names_to_users_table.php`~~ 
   - Reason: Empty duplicate migration

2. ~~`2025_09_23_143734_create_allocation_logs_table.php`~~ 
   - Reason: Superseded by `2025_10_02_000004_recreate_allocation_logs_table.php`

3. ~~`2025_10_02_000002_add_office_id_to_allocation_logs_table.php`~~ 
   - Reason: office_id already included in recreate migration

4. ~~`2025_10_02_000003_fix_office_id_column.php`~~ 
   - Reason: Fix already handled in recreate migration

## Important Notes

### For New Installations
Run all migrations in order:
```bash
php artisan migrate
```

### For Existing Installations
If you've already run the old migrations, you may need to:
```bash
php artisan migrate:status  # Check which migrations have run
php artisan migrate         # Run any new migrations
```

### For Fresh Installation (Development Only)
```bash
php artisan migrate:fresh --seed
```
⚠️ **WARNING**: This will drop all tables and data!

## Migration Naming Convention

Format: `YYYY_MM_DD_HHMMSS_description.php`

Examples:
- `2025_01_20_000000_add_grade_photo_to_applications_table.php`
- `2025_10_02_000004_recreate_allocation_logs_table.php`

## Best Practices

1. **Never edit** migrations that have already been run in production
2. **Create new** migrations to modify existing tables
3. **Use descriptive names** that explain what the migration does
4. **Include rollback** logic in the `down()` method
5. **Test migrations** on a copy of the database first

## Troubleshooting

### Column already exists error
If you get "column already exists", the migration may have partially run. Check with:
```bash
php artisan tinker
Schema::hasColumn('table_name', 'column_name')
```

### Table already exists error
The table was created by a previous migration. Check migration status:
```bash
php artisan migrate:status
```

### Foreign key constraint error
Ensure parent tables exist before creating relationships. Check migration order.

## Database Connections

The system uses multiple database connections:
- **mysql** (default): Main SureScholarship database
- **e_kalinga**: E-Kalinga integration (budget allocations, consolidated transactions)
- **e_tala**: E-Tala integration (demographics, validated beneficiaries)

Make sure all connections are configured in `.env` file.

