# RMI Error Debugging Guide

## Quick Steps to Debug "Error saving progress" Issue

### Step 1: Check Database Setup
1. **Run the SQL setup:**
   - Open phpMyAdmin or your MySQL client
   - Select your database (probably the one used by your CodeIgniter app)
   - Run the SQL file: `quick_db_setup.sql`

### Step 2: Test Database Connectivity
1. **Access the debug endpoint:**
   ```
   http://localhost/Interiskting/farhan/index.php/rmi/debug_db
   ```
   - This will show if tables exist and if database operations work
   - Look for any error messages in the JSON response

### Step 3: Test AJAX from Browser Console
1. **Open the RMI page:**
   ```
   http://localhost/Interiskting/farhan/index.php/rmi
   ```

2. **Open browser Developer Tools (F12)**

3. **Run debug function in console:**
   ```javascript
   RMI.debugAjaxSave();
   ```
   - This will test both database connectivity and AJAX saving
   - Check console for detailed error messages

### Step 4: Check for Common Issues

**Issue 1: Database Not Connected**
- Check `application/config/database.php`
- Ensure MySQL is running in XAMPP
- Verify database name, username, password

**Issue 2: Tables Don't Exist**
- Run `quick_db_setup.sql` in phpMyAdmin
- Or access `/rmi/debug_db` to auto-create tables

**Issue 3: Permission Issues**
- Ensure database user has INSERT/UPDATE permissions
- Check if `completed_by` field conflicts (user ID issues)

**Issue 4: AJAX/Routing Issues**
- Check if CodeIgniter routing works: `/rmi/debug_db`
- Verify `base_url` is set correctly in CodeIgniter config

### Step 5: Check Error Logs

**PHP Error Log:**
- Check XAMPP logs: `xampp/apache/logs/error.log`

**Browser Console:**
- Look for JavaScript errors
- Check Network tab for failed AJAX requests

**CodeIgniter Logs:**
- Check `application/logs/` folder for recent error logs

### Step 6: Manual Database Test

**Test if you can manually insert data:**
```sql
INSERT INTO rmi_point_progress 
(parameter_key, phase_level, point_identifier, point_text, is_completed, completed_by, created_at, updated_at) 
VALUES 
('manual_test', 1, 'test_point', 'Manual test', 1, 1, NOW(), NOW());
```

If this fails, it's a database setup issue.

### Step 7: Debug Output

**Run these in browser console for detailed info:**
```javascript
// Test notification system
RMI.showNotification('Test message', 'success');

// Test database debug
RMI.debugAjaxSave();

// Check if base_url is correct
console.log('Base URL:', base_url);

// Check if jQuery is loaded
console.log('jQuery version:', $.fn.jquery);
```

### Common Error Messages and Solutions

**"Invalid request"** = AJAX detection failed
- Check if X-Requested-With header is sent

**"Missing required fields"** = Data not being sent properly
- Check JavaScript console for data being sent

**"Database error"** = SQL execution failed
- Check database connection and table structure

**"Failed to save progress - no rows affected"** = Insert/Update failed silently
- Check for unique constraint violations
- Verify data types match table structure

### Quick Fix Commands

**Reset everything:**
```javascript
// Clear any saved state
RMI.clearAccordionState();
localStorage.clear();

// Test fresh save
RMI.debugAjaxSave();
```

**Force table creation:**
- Access: `http://localhost/Interiskting/farhan/index.php/rmi/debug_db`

Let me know what specific error messages you see in the console or debug responses!
