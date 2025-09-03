# Role Switching Functionality

This document describes the implementation of a role switching system that allows users to have a maximum of two roles and switch between them seamlessly.

## Overview

The system allows administrators to assign up to two roles to users, and users can switch between these roles to access different functionality based on their current active role.

## Features

- **Maximum 2 Roles**: Users can have up to 2 roles assigned
- **Role Switching**: Users can switch between their assigned roles
- **Primary Role**: First assigned role becomes the primary role
- **Session Management**: Current role is stored in session for web app
- **Local Storage**: Current role is stored in AsyncStorage for mobile app
- **Admin Panel**: Administrators can assign/edit user roles
- **Dashboard Integration**: Role switcher appears on dashboard when user has multiple roles

## Backend Implementation

### Models

#### User Model (`app/Models/User.php`)
- Added methods to get current role, primary role, and secondary role
- Enhanced role relationship handling
- Added role validation methods

#### Role Model (`app/Models/Role.php`)
- Standard Entrust role model
- Supports permissions and role management

### Controllers

#### UserController (`app/Http/Controllers/UserController.php`)
- Updated to handle maximum 2 roles
- Added validation for role limits
- Enhanced role assignment logic

#### RoleSwitchController (`app/Http/Controllers/RoleSwitchController.php`)
- New controller for role switching functionality
- Methods:
  - `switchRole()`: Switch to a different role
  - `getCurrentRole()`: Get current active role
  - `getUserRoles()`: Get all user roles

### Routes

#### Web Routes (`routes/web.php`)
```php
Route::post('role/switch', 'RoleSwitchController@switchRole')->name('role.switch');
Route::get('role/current', 'RoleSwitchController@getCurrentRole')->name('role.current');
Route::get('role/user-roles', 'RoleSwitchController@getUserRoles')->name('role.user-roles');
```

#### API Routes (`routes/api.php`)
```php
Route::post('/role/switch', 'RoleSwitchController@switchRole');
Route::get('/role/current', 'RoleSwitchController@getCurrentRole');
Route::get('/role/user-roles', 'RoleSwitchController@getUserRoles');
```

### Views

#### User Edit/Create Forms
- Updated to show maximum 2 roles message
- Added client-side validation to prevent selecting more than 2 roles
- Enhanced role display with proper labels

#### Dashboard (`resources/views/dashboard/index.blade.php`)
- Added role switcher component
- Shows current role and available roles
- Allows users to switch between roles
- Only displays when user has multiple roles

#### User Show View
- Displays all assigned roles
- Shows primary role indicator

## Mobile App Implementation

### Components

#### RoleSwitcher (`Restrack_React_App/components/RoleSwitcher.js`)
- React Native component for role switching
- Modal-based interface for role selection
- Integrates with AsyncStorage for role persistence
- Only shows when user has multiple roles

### Screens

#### Home Screen (`Restrack_React_App/screens/Home.js`)
- Integrated RoleSwitcher component
- Handles role change callbacks
- Updates UI based on current role

#### ElifDashboard (`Restrack_React_App/screens/ElifDashboard.js`)
- Also includes RoleSwitcher component
- Consistent role switching across dashboards

#### Onboarding (`Restrack_React_App/screens/Onboarding.js`)
- Enhanced login to handle multiple roles
- Stores current role in AsyncStorage
- Maintains backward compatibility

## Database Structure

### Tables

#### `role_user` (Pivot Table)
- `user_id`: Foreign key to users table
- `role_id`: Foreign key to roles table
- `created_at`: Timestamp
- `updated_at`: Timestamp
- Composite primary key: `(user_id, role_id)`

### Migration

#### `2024_01_01_000000_ensure_role_user_table_structure.php`
- Ensures proper table structure
- Adds timestamps if missing
- Sets up proper foreign key constraints

## Usage

### For Administrators

1. **Assigning Roles**:
   - Go to Users → Edit User
   - Select up to 2 roles from the checkbox list
   - First selected role becomes primary role
   - Save changes

2. **Managing User Roles**:
   - View user details to see assigned roles
   - Edit user to modify role assignments
   - Maximum 2 roles enforced

### For Users

1. **Web Dashboard**:
   - Role switcher appears if user has multiple roles
   - Click on role buttons to switch
   - Current role is highlighted
   - Page reloads to reflect new role permissions

2. **Mobile App**:
   - Role switcher button appears in dashboard
   - Tap to open role selection modal
   - Select new role to switch
   - Role change is persisted locally

## API Endpoints

### Switch Role
```
POST /api/role/switch
{
    "role_id": 5
}
```

### Get Current Role
```
GET /api/role/current
```

### Get User Roles
```
GET /api/role/user-roles
```

## Security Considerations

- Users can only switch to roles they actually have
- Role switching requires authentication
- Session-based role storage for web app
- Local storage for mobile app
- No elevation of privileges through role switching

## Configuration

### Environment Variables
No additional environment variables required.

### Dependencies
- Laravel Entrust for role management
- Existing authentication system
- Standard Laravel session management

## Testing

### Backend Tests
- Test role assignment limits
- Test role switching functionality
- Test role validation
- Test session management

### Frontend Tests
- Test role switcher UI
- Test role selection validation
- Test role persistence
- Test dashboard integration

## Troubleshooting

### Common Issues

1. **Role Switcher Not Appearing**:
   - Check if user has multiple roles assigned
   - Verify role relationships in database
   - Check authentication status

2. **Role Switching Fails**:
   - Verify user has the target role
   - Check session configuration
   - Review error logs

3. **Mobile App Role Issues**:
   - Check AsyncStorage permissions
   - Verify role data structure
   - Check network connectivity for API calls

### Debug Information

- Check browser console for JavaScript errors
- Review Laravel logs for backend errors
- Verify database role assignments
- Check session configuration

## Future Enhancements

- Role-based dashboard customization
- Role switching audit logs
- Advanced role permissions
- Role switching notifications
- Bulk role management for administrators

## Support

For technical support or questions about the role switching functionality, please refer to the development team or create an issue in the project repository.
