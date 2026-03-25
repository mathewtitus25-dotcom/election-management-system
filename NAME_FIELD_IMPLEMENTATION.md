# Name Field Split Implementation

## Overview
Successfully split the "Full Name" field into **First Name**, **Middle Name** (optional), and **Last Name** in both voter and candidate registration forms.

## Changes Made

### 1. Candidate Registration Form
**File**: `resources/views/candidate/register.blade.php`
- Replaced single "Full Name" field with three fields:
  - **First Name** (required) - `col-md-4`
  - **Middle Name** (optional) - `col-md-4`
  - **Last Name** (required) - `col-md-4`
- Added red asterisk (*) to indicate required fields
- Maintained proper Bootstrap grid layout

### 2. Candidate Controller
**File**: `app/Http/Controllers/CandidateController.php`
- Updated validation rules:
  ```php
  'first_name' => 'required|string|max:255',
  'middle_name' => 'nullable|string|max:255',
  'last_name' => 'required|string|max:255',
  ```
- Added name combination logic:
  ```php
  $fullName = trim($request->first_name . ' ' . ($request->middle_name ?? '') . ' ' . $request->last_name);
  $fullName = preg_replace('/\s+/', ' ', $fullName);
  ```
- Stores combined name in existing `name` column

### 3. Voter Registration Form
**File**: `resources/views/auth/register.blade.php`
- Applied same three-field split as candidate form
- Maintained consistent styling and validation
- Added "Optional" placeholder for middle name

### 4. Voter Registration Controller
**File**: `app/Http/Controllers/RegistrationController.php`
- Updated validation rules to match candidate controller
- Added same name combination logic
- Stores combined name in existing `name` column

## Key Features

✅ **No Database Changes** - Continues using existing `name` column  
✅ **Required Validation** - First and last names are mandatory  
✅ **Optional Middle Name** - Marked as `nullable` in validation  
✅ **Smart Combination** - Removes extra spaces when middle name is empty  
✅ **Consistent UI** - Both forms use same layout pattern  
✅ **Exam-Friendly** - Simple, clean implementation  

## Example Usage

**Input:**
- First Name: `John`
- Middle Name: (empty)
- Last Name: `Doe`

**Stored in Database:**
```
name: "John Doe"
```

**Input:**
- First Name: `John`
- Middle Name: `Michael`
- Last Name: `Doe`

**Stored in Database:**
```
name: "John Michael Doe"
```

## Testing Checklist

- [ ] Register a candidate with all three names
- [ ] Register a candidate without middle name
- [ ] Register a voter with all three names
- [ ] Register a voter without middle name
- [ ] Verify validation errors for missing first/last name
- [ ] Check that names are properly combined in database
- [ ] Verify old() helper works for form repopulation on errors
