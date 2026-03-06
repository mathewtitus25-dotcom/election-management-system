# Three-Panchayat Digital Election Management System

## Overview
A comprehensive digital election management system for three panchayats with role-based access control, voter registration workflow, candidate management, and secure voting capabilities.

## User Roles and Authentication
- **Internet Identity integration** for secure authentication
- **Three user roles** with separate login portals:
  - Admin: System-wide management capabilities
  - Booth Level Officer (BLO): Panchayat-specific voter management
  - Voter: Registration and voting access

## Public Landing Page
- Introduction to the election system
- Access to voter registration form
- Candidate application form
- Login options for all three user roles
- Public election results display

## Voter Registration and Management
- **Registration form** with fields: name, date of birth, voter ID, email, password, panchayat selection
- **On-screen OTP simulation** for verification (no actual email sending)
- **Two-step approval process**:
  1. Voter submits registration
  2. Assigned BLO approves/rejects registration
- Only approved voters gain voting rights
- Voters restricted to their registered panchayat

## BLO (Booth Level Officer) Functionality
- BLOs assigned to specific panchayats by Admin
- View pending voter registrations within assigned panchayat only
- Approve or reject voter registration requests
- Dashboard showing panchayat-specific voter statistics

## Admin Functionality
- **BLO Management**: Create, assign to panchayats, deactivate BLO accounts
- **Panchayat Configuration**: Set up three panchayats
- **Election Parameters**: Configure election start and end dates
- **Candidate Management**: Approve or reject candidate applications
- System-wide oversight and reporting

## Candidate Application and Management
- Public candidate application form
- Candidates select their panchayat for contest
- Admin approval required to appear on ballot
- Approved candidates displayed on voting interface

## Voting System
- **Voting eligibility**: Only approved voters can access voting
- **Single vote restriction**: Each voter can vote only once
- **Panchayat-based voting**: Voters see candidates from their panchayat only
- **Real-time results**: Public tally updates during voting phase
- Secure vote recording and tamper-proof storage

## Results and Reporting
- **Public results page** with real-time vote counts
- **Final results** showing panchayat-wise winners
- **Transparent reporting** with vote tallies and statistics
- Results accessible without authentication

## Data Storage Requirements
The backend must store:
- User accounts (Admin, BLO, Voter) with roles and panchayat assignments
- Voter registration requests and approval status
- Candidate applications and approval status
- Election configuration (dates, panchayat details)
- Vote records with panchayat association
- Election results and statistics

## Key Features
- Role-based dashboard navigation
- On-screen status messages and confirmations
- Audit-friendly data tracking
- Transparent workflow visibility
- Real-time vote counting
- Secure single-vote enforcement
