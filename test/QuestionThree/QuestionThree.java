import java.io.IOException;

public class QuestionThree {

  // function that prints out numbers in between the 2 given parameters
  public static void inBetween(int startNumber, int endNumber){
    System.out.println("The first number is : " + startNumber);
    System.out.println("The last number is : " + endNumber);

    // if numbers are same
    if(startNumber == endNumber)
    System.out.println(startNumber);

    // loops through the variables and print the numbers in between
    else
    for(int x = startNumber; x <= endNumber; x++)
    System.out.println(x);
  } // inBetween

  public static void main (String[] args){
    // takes the arguments from the terminal
    int startNumber = Integer.parseInt(args[0]);
    int endNumber = Integer.parseInt(args[1]);

    try{
      // make sure the arguments are between 1 and 1mil
      if(startNumber < 1 || endNumber < 1 || startNumber > 1000000 || endNumber > 1000000)
        throw new IllegalArgumentException
          ("The arguments given must be between 1 and 1 million.");

      if(startNumber > endNumber)
        throw new IllegalArgumentException
          ("The first argument should be smaller than the second one.");

      // run the method
      inBetween(startNumber, endNumber);
    } // try

    catch(Exception exception){
      System.err.println(exception);
    } // catch

  } // main

}
