# AUCTION SYSTEM

### 1. To-Do List for the Web Auction Application

#### 1.1. Home Page

- [X] Implement user login and navigation to the home page upon successful login.
    - [X] Api
- [X] Display a paginated list of auction items (10 items per page).
    - [X] Api
- [X] Implement a search box to filter items by Name and Description fields.
    - [X] Api
- [X] Add sorting functionality for the Price column.
    - [X] Api

#### 1.2. Item Details Page

- [X] Create a details page for each auction item.
    - [X] Api
- [X] Add a countdown timer to show the remaining time before bidding closes.
- [X] Implement a bidding system with a minimum bid increment (e.g., $1 more than the last bid).
    - [X] Api
- [X] Allow users to submit bids and ensure multiple bids by the same user are allowed if their bid is not the highest.
    - [X] Api
- [X] Implement the auto-bidding feature:
    - [X] Add a checkbox to activate auto-bidding.
    - [X] Automatically outbid other users by $1 when auto-bidding is activated.

#### 1.3. Auto-Bidding Configuration

- [X] Create a separate page for configuring auto-bidding parameters.
    - [X] Api
- [X] Allow users to set the maximum bid amount for auto-bidding.
    - [X] Api
- [X] Ensure the maximum amount is shared across all items with auto-bidding enabled.
    - [X] Api
- [X] Implement bid alert notifications based on a user-defined percentage of the maximum bid amount.
    - [X] Api
- [X] Stop auto-bidding and notify the user when funds are insufficient.
    - [X] Api

#### 1.4. Administrator Dashboard Page

- [X] Implement a page where administrators can:
    - [X] Add new auction items.
        - [X] Api
    - [X] View details of a single item.
        - [X] Api
    - [X] Modify the fields of an item.
        - [X] Api
    - [X] Remove an item.
        - [X] Api
- [X] Ensure regular users can only view the list and details of auction items.
    - [X] Api

#### 1.5. Additional Tasks

- [X] Implement user authentication and role management (admin vs. regular user).
    - [X] Api
- [X] Design the user interface with good usability principles.
- [X] Handle concurrency issues, especially with the auto-bidding feature.
    - [X] Api
- [X] Implement notifications for bid alerts and auto-bidding status.
    - [X] Api
- [X] Ensure the application is secure and handles edge cases (e.g., simultaneous bids).
    - [X] Api

#### 1.6. Testing

- [ ] Perform unit tests for each feature.
- [ ] Conduct integration testing for the entire application.
- [ ] Ensure usability testing to verify a good user experience.
- [ ] Test for concurrency issues and resolve any conflicts.

### 2. Deployment

#### 2.1. Prerequisite

1. PHP >= 8.3
2. Laravel 11.18
3. Composer
4. NodeJs 20.0
5. NPM

#### 2.2. Installation

##### 2.2.1. Backend Laravel

1. Download the private file from the drive

2. Extract the private file

3. Clone the BE

```bash
git clone https://github.com/Syafiqq/auction_system_be.git
```

4. Copy predefined data to the folder

```bash
cp -r be/. auction_system_be/
```

5. Change directory

```bash
cd auction_system_be
```

5. Install dependencies

```bash
composer install
```

6. Run jobs

```bash
php artisan queue:work --queue=autobid_preparation,autobid_execution,mailer,default --sleep=1 --tries=1
```

7. Run cron

```bash
php artisan schedule:work
```

7. If notting happens (because the cron is scheduled at 12AM) can use this command below
```bash
php artisan tinker

use App\Domain\UseCase\Abstract\SetAuctionWinnerUseCase;

app(SetAuctionWinnerUseCase::class)->execute();
```

8. Run the server
```bash
php artisan serve
```

9. Clone the FE

```bash
git clone https://github.com/Syafiqq/auction_system_fe.git
```

10. Copy predefined data to the folder

```bash
cp -r fe/. auction_system_fe/
```

11. Change directory

```bash
cd auction_system_fe
```

12. Install dependencies

```bash
npm install
```

13. Start the NextJs development server

```bash
npm run dev
```

### Part 2 Improvement

- Improve the setup process and/or instructions
  - The app is now stateless
- Improve validation messages
  - Now the app can properly show the validation messages
- Improve navigation and general UI/UX of app
  - Fix issues when removing auction item
  - Fix session end handler
  - Fix redirection for some pages
- Present amounts as dollar amounts, include cent handling.
  - Done

### Part 2 Issue
- The app is stateless, so no CSRF is configured
- The id should not be placed as url path or url query
- The websocket should be authenticated
- There is a delay when websocket is emitted maybe 5-10 seconds

### Part 1 Issue
- Notification Handling: The current notification system is closely integrated with the main workflow, which can impact
  efficiency. To enhance performance, consider delegating notifications, such as those for "auction ended" events, to a
  separate worker or service. This would allow notifications to be processed independently of the primary workflow,
  improving overall system responsiveness.
- Bidding Process Development: Further development is needed for both manual and automated bidding processes.
  Establishing clear strategies for managing different types of bids will help ensure a seamless and intuitive user
  experience.
- Bidding Locking Mechanism: The system currently lacks a robust locking mechanism for the bidding process. Implementing
  a locking system, potentially utilizing a FIFO approach and Redis, could prevent conflicts and maintain the integrity
  of bids.
- Transaction Management: Some application processes are not fully transactional, which poses a risk of incomplete or
  inconsistent data if an error occurs. Ensuring that all critical processes are handled transactionally would mitigate
  this risk and improve data integrity.
- User Interface Enhancements: The user interface could benefit from additional features to enhance usability and
  engagement. Consider incorporating features such as real-time updates, interactive elements, and intuitive navigation
  to create a more dynamic and user-friendly experience.
- Autobid Maximum Amount: The autobid's maximum amount is only applicable to subsequent bids and is not adjusted after a
  successful bid. This may lead to user confusion or unintended bidding behavior, and should be addressed to align with
  user expectations.
- Autobid Value Reduction: The system currently allows the reduction of an autobid value even when the autobid is active
  and has potential to win. This could compromise the integrity of the bidding process and requires reevaluation.
- Notification Workflow: The notification workflow is currently integrated within the main process flow, introducing
  unnecessary overhead. Decoupling the notification process from the main workflow would enhance overall system
  performance.
- Unit Testing: There is limited time available for unit testing, which could affect the reliability of the system.
  Allocating time for comprehensive unit testing is essential for ensuring code quality and system stability.
- Autobid and Manual Bid Interaction: The autobid system uses a round-robin approach, which does not utilize a queue.
  This decision is based on the need to remove ongoing autobid processes when a manual bid is placed, and to create a
  new autobid process. This approach may need further evaluation to ensure optimal functionality.
- Image Upload Constraints: Currently, the system does not impose constraints on image upload sizes. Implementing size
  limitations would help manage storage and performance, ensuring that uploaded images are appropriately sized.
- Current Price information: Current price information is updated only when a bid is placed. This may lead to discrepancies
  in the displayed price if multiple users are bidding simultaneously. Implementing real-time price updates would enhance
  user experience and provide accurate information.
