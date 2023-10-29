

# Register Route - Frontend Developer Guide

## Introduction

This guide is intended for frontend developers working on our Laravel application. It explains how to use the "Register" route, which is a crucial part of our user registration and authentication system.


## 1. Overview

The "Register" route is responsible for user registration. It allows new users to create accounts on our platform.

## 2. Route Description

- **Route URL**: `/register`
- **HTTP Method**: POST

## 3. Input Fields

When sending a POST request to the "Register" route, the frontend must include the following input fields as part of the request body:

1. `name`: User's name. (Data Type: String)
2. `email`: User's email address. (Data Type: String)
3. `phone`: User's phone number. (Data Type: String, **Optional**)
4. `age`: User's age group. (Data Type: String)
5. `exercise_history`: User's exercise history. (Data Type: Boolean)
6. `fitness_level`: User's fitness level. (Data Type: String, Nullable)
7. `form_of_workout`: User's preferred form of workout. (Data Type: String)
8. `fitness_goal`: User's fitness goal. (Data Type: Text, Long Text)
9. `goal_timeline`: User's goal timeline. (Data Type: Integer)
10. `city`: User's city or location. (Data Type: String)
11. `info_source`: Source of information about the application. (Data Type: Text, Long Text)

The data types indicate the expected data format for each input field.

## 4. Validation

The Laravel backend performs validation on the input data. Ensure that the frontend validates the data before sending the request. Ensure that all fields apart from the optional field is present on all request.

## 5. Registration Process

- The client sends a POST request to the `/register` route with the required input fields.
- The server processes the request, validates the data, and registers the user if the data is valid.
- The user's account is created.
- Response is sent back to the client.

## 6. Response

Upon successful registration, the backend will respond with:

- A success message, typically in JSON format.
    ```JSON
    {
        "status": "success",
        "message": "User registration successful",
        "user": {
            "name": "William Hall",
            "phone": "111-222-3333",
            "email": "williamhall@example.com",
            "age": "31-40",
            "exercise_history": true,
            "fitness_level": "pro",
            "form_of_workout": "strength weight training",
            "fitness_goal": "Build lean muscle and endurance",
            "goal_timeline": 8,
            "city": "Maiduguri",
            "info_source": "Personal trainer recommendation",
            "updated_at": "2023-10-29T21:35:31.000000Z",
            "created_at": "2023-10-29T21:35:31.000000Z",
            "id": 3
        }
     }
    ```

- If email is duplicate
    ```JSON
    {
        "status": "error",
        "message": "Email address is already in use."
    }
    ```

- Other errors:
    ```JSON
    {
        "status": "error",
        "message": "User registration failed",
    }
    ```

## 7. Errors Handling

The backend may respond with error messages in case of invalid input or other issues. Frontend developers should handle error responses gracefully and provide informative feedback to users.

## 8. Usage Examples

### JavaScript Example (using Fetch API)

```javascript
const registerUser = async (userData) => {
  try {
    const response = await fetch('/register', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(userData),
    });

    if (response.ok) {
      const data = await response.json();
      // Handle successful registration response (e.g., show success message).
    } else {
      const errorData = await response.json();
      // Handle error response (e.g., display error message to the user).
    }
  } catch (error) {
    // Handle network or other unexpected errors.
  }
};

// Example of how to use the registerUser function
const userData = {
    "name": "William Hall",
    "email": "williamhall@example.com",
    "phone": "111-222-3333",
    "age": "31-40",
    "exercise_history": true,
    "fitness_level": "pro",
    "form_of_workout": "strength weight training",
    "fitness_goal": "Build lean muscle and endurance",
    "goal_timeline": 8,
    "city": "Maiduguri",
    "info_source": "Personal trainer recommendation"
};

registerUser(userData);

```

## Conclusion

The "register" route is a crucial part of our user registration system. Frontend developers should ensure they provide valid user data and handle both successful and error responses from the backend appropriately.

If you encounter any issues or need further assistance, please reach out for support.

