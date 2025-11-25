# API Documentation

Simple REST API for the Jobstartbörse application.

## Base URL

```
/api
```

## Authentication

All API requests require an API key passed as a query parameter:

```
?api_key=your_api_key_here
```

The API key is configured in `config/jobstartboerse.php` under `api.key`.

**Example:**
```
GET /api/job-fairs?api_key=your_api_key_here
```

## Endpoints

### Job Fairs

#### List Job Fairs

```
GET /api/job-fairs
```

Returns all public job fairs with basic info.

**Response:**
- `id` - Job fair ID
- `display_name` - Display name
- `description` - Description
- `is_public` - Whether the job fair is public
- `are_exhibitors_public` - Whether exhibitors are publicly visible
- `floor_plan_link` - Link to floor plan
- `lounge_registration_ends_at` - Registration deadline
- `dates` - Array of job fair dates
- `locations` - Array of locations
- `exhibitors_count` - Number of exhibitors

#### Get Single Job Fair

```
GET /api/job-fairs/{id}
```

Returns a single job fair with optional includes.

**Query Parameters:**
- `include` (optional) - Comma-separated list of relations to include:
  - `exhibitors` - List of exhibitors (only if `are_exhibitors_public` is true)
  - `professions` - List of professions offered by exhibitors
  - `degrees` - List of degrees offered by exhibitors
  - `lounge_participations` - Job lounge participants
  - `school_registrations` - School registrations

**Example:**
```
GET /api/job-fairs/1?api_key=your_api_key_here&include=exhibitors,professions
```

### School Registration

#### Register School

```
POST /api/job-fairs/{id}/school-registration
```

Register a school and classes for a job fair. Only available for public job fairs.

**Request Body:**
```json
{
  "school_name": "Example School",
  "school_type": "Gymnasium",
  "school_zipcode": "12345",
  "school_city": "Berlin",
  "teacher": "John Doe",
  "teacher_email": "john@example.com",
  "teacher_phone": "+49123456789",
  "classes": [
    {
      "name": "10a",
      "time": "09:00-10:30",
      "students_count": 25
    },
    {
      "name": "10b",
      "time": "10:30-12:00",
      "students_count": 28
    }
  ]
}
```

**Validation:**
- `school_name` - Required, max 255 chars
- `school_type` - Optional, max 255 chars
- `school_zipcode` - Required, 5 digits
- `school_city` - Required, max 255 chars
- `teacher` - Required, max 255 chars
- `teacher_email` - Required, valid email, max 255 chars
- `teacher_phone` - Optional, max 255 chars
- `classes` - Required array, at least 1 class
- `classes.*.name` - Required, max 255 chars
- `classes.*.time` - Required, max 255 chars
- `classes.*.students_count` - Required, integer, min 1

**Response:**
Returns the created registration with status `201 Created`.

## Error Responses

**Validation Errors (422):**
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "field_name": ["Error message"]
  }
}
```

**Authentication Errors (401):**
```json
{
  "message": "Unauthorized"
}
```
