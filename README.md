# USSD-Based-Class-Representative-Voting-System


## Overview
This project is a mobile-based voting system that allows students to register and vote for their preferred class representative using USSD (Unstructured Supplementary Service Data). It is designed to work on any mobile phone, including basic feature phones, without needing internet access. The system ensures only registered users can vote and prevents multiple voting.

## Key Features
- **USSD Interface**: User-friendly menu-based navigation for low-end phones.
- **User Registration**: Students register with their name and student ID before voting.
- **Secure Voting**: Each registered user can vote only once.
- **Live Results**: Users can view the real-time voting results.
- **Voter Verification**: Phone number-based tracking prevents duplicate voting.


## System Components
- **USSD Gateway**: Africa's Talking (or similar)
- **Backend**: PHP
- **Database**: MySQL

## Use Case Flow
1. User dials the USSD code (*384*2906#).
2. Main Menu displays: Register, Vote, View Results.
3. If not registered: Prompt for full name and student ID. Save details linked to their phone number.
4. If registered and not voted: Show candidates list. Record vote and update has_voted = true. Show updated results.
5. If already voted: Display message: "You have already voted."
6. Anyone can view live results.

## Target Users
- University or high school students
- Institutions running class or club elections

## Conclusion
The USSD-Based Class Representative Voting System is a lightweight, accessible solution for student elections, particularly suited to environments where internet access is limited. By combining simple mobile interactions with secure backend logic, the system ensures fairness, ease of use, and wide accessibility for all students. Its modular design also makes it easy to extend for other voting or survey use cases in educational institutions.


## Usage
- **Register**: Follow the prompts to enter your full name and student ID.
- **Vote**: Select a candidate from the list to cast your vote.
- **View Results**: Check the current voting results.

