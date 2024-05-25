Certainly, let's add the additional condition for a 7-day break between the completion of the first round and the start of the second round if all teams have played half of their matches. This condition ensures a rest period between the two rounds of the league season.

So, the updated conditions are:

1. **Total Matches per Team:**
   - Each team should play a total of `(Total of teams - 1) * 2` matches.

2. **Play Twice Home and Away:**
   - Each team should play against every other team twice, once at home and once away.

3. **Avoid Consecutive Home/Away Matches:**
   - No team should play more than two consecutive matches at home or away.

4. **Consider Exceptional Dates:**
   - Avoid scheduling matches during exceptional dates specified in the `exceptional_date` table.

5. **Reschedule Skipped Matches:**
   - If a match was skipped due to an exceptional date, reschedule it 3 days after the end date of the exceptional event.

6. **Distribute Matches Based on Day and Number of Matches:**
   - Distribute matches evenly across the specified match days (`day_match` table) and consider the number of matches for each day.

7. **Consider Venue Sharing:**
   - Teams sharing the same venue should not have home matches scheduled on the same day.

8. **Set Default Time and Increment for Multiple Matches on the Same Day:**
   - Set the default time for matches to 16:00:00 EAT.
   - If there are more than one match on the same day, increment the time by 120 minutes for each subsequent match.

9. **Display Dates Dynamically:**
   - Display the dates dynamically based on the selected day for scheduling.

10. **7-Day Break Between Rounds:**
   - If all teams have completed half of their matches for the first round, introduce a 7-day break before starting the second round.

If you have any specific questions or if you'd like help with the SQL queries for this additional condition, please let me know!