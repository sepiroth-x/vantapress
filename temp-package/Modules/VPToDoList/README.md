# VP To Do List Module

A beautiful, feature-rich task management system for VantaPress CMS with project-based organization.

## Features

### 📁 **Project Management**
- Create and organize projects with custom colors
- Track project progress automatically
- Set project timelines with start and due dates
- Project status tracking (Active, On Hold, Completed, Archived)
- Visual progress bars for each project

### ✅ **Task Management**
- Create detailed tasks with rich descriptions
- Organize tasks by projects
- 5 status levels: To Do, In Progress, Review, Completed, Blocked
- 4 priority levels: Low, Medium, High, Urgent
- Pin important tasks to keep them at the top
- Add tags for better organization
- Set due dates and track overdue tasks
- Bulk actions for efficient task management

### 🎨 **Beautiful UI**
- Modern, clean interface matching VantaPress theme
- Color-coded projects for quick identification
- Status and priority badges with icons
- Progress visualization
- Responsive design for all devices
- Dark mode support

### 👤 **User-Specific**
- Each user has their own workspace
- Tasks and projects are private to the user
- Track your own progress independently

### 📊 **Smart Filtering & Organization**
- Filter by status, priority, project
- View overdue and due today tasks
- Group tasks by project, status, or priority
- Tab-based navigation (All, To Do, In Progress, Review, Completed, Overdue)
- Reorderable tasks and projects

### 🔔 **Visual Indicators**
- Navigation badges showing active projects and pending tasks
- Overdue task warnings
- Pinned task highlighting
- Project color coding
- Status and priority color system

## Installation

The module is automatically installed with VantaPress. To enable it:

1. Go to **Extensions → Modules**
2. Find **VP To Do List**
3. Enable the module
4. Migrations will run automatically

## Usage

### Creating a Project

1. Navigate to **To Do List → Projects**
2. Click **Create Project**
3. Fill in:
   - **Name**: Your project name
   - **Description**: What the project is about
   - **Color**: Choose a color to identify this project
   - **Start Date**: When the project begins
   - **Due Date**: When the project should be completed
   - **Status**: Active, On Hold, Completed, or Archived
4. Click **Create**

### Creating a Task

1. Navigate to **To Do List → My Tasks**
2. Click **Create Task**
3. Select or create a project
4. Fill in:
   - **Title**: Brief task description
   - **Description**: Detailed information (supports formatting)
   - **Status**: Current state of the task
   - **Priority**: How urgent the task is
   - **Due Date**: When the task should be completed
   - **Pin**: Toggle to keep task at the top
   - **Tags**: Add relevant tags
5. Click **Create**

### Managing Tasks

- **Mark as Complete**: Click the complete button on any task
- **Edit**: Click edit to modify task details
- **Delete**: Remove tasks you no longer need
- **Reorder**: Drag and drop to change task order
- **Filter**: Use filters to find specific tasks
- **Group**: Organize tasks by project, status, or priority
- **Bulk Actions**: Select multiple tasks for bulk operations

### Using Tabs

Navigate between task views:
- **All Tasks**: See everything
- **To Do**: Tasks not yet started
- **In Progress**: Currently working on
- **Review**: Waiting for review
- **Completed**: Finished tasks
- **Overdue**: Tasks past their due date

## Database Schema

### vp_projects
- Project information
- User ownership
- Timeline and status
- Color customization

### vp_tasks
- Task details
- Project association
- Status and priority
- Due dates and completion tracking
- Tags and ordering

## Technical Details

- **Models**: `Modules\VPToDoList\Models\Project`, `Modules\VPToDoList\Models\Task`
- **Resources**: Filament-based admin interface
- **User Isolation**: All queries are automatically scoped to the authenticated user
- **Relationships**: Projects have many tasks, tasks belong to projects and users
- **Soft Features**: Task pinning, tag support, bulk operations

## Color System

**Status Colors:**
- To Do: Gray
- In Progress: Blue
- Review: Orange
- Completed: Green
- Blocked: Red

**Priority Colors:**
- Low: Gray
- Medium: Blue
- High: Orange
- Urgent: Red

## Tips

1. **Use Projects to Organize**: Create projects for different areas of work
2. **Set Realistic Due Dates**: Help yourself stay on track
3. **Use Priorities**: Focus on what matters most
4. **Pin Important Tasks**: Keep critical tasks visible
5. **Review Regularly**: Check the overdue tab frequently
6. **Use Tags**: Create your own organization system
7. **Track Progress**: Watch project progress bars for motivation

## Support

For issues or feature requests, contact the VantaPress team.

---

**Version**: 1.0.0  
**Author**: VantaPress  
**License**: Included with VantaPress CMS
