To run:   
    go run main.go avgtimes short.json  

## Expected time: 1.5 hours  

## Task: You will be given the source code to a program with 1 existing feature.  

  The task is to add 1 more feature, described below. There is a bonus goal of improving the current feature.  

  The existing feature, “avgtimes”, is:  
    Read in a JSON file of “requests” to find average timings by category  
    Print those averages out in sorted order  

  The new feature, “numpairs”, is:  
    Read in a JSON files of “requests”  
    Count the amount of pairs whose categories satisfy one-anothers regexes.  

  Included are two data files, “short.json” and “long.json”.   

  The existing feature works correctly for “short.json” but fails on “long.json”. Both features work on the same data model and input/output in similar ways, so there is lots of potential for code-reuse and abstraction.  

  Errors in the input data should be handled by assuming Go’s zero-values where possible and exiting otherwise.  

## Evaluation points:  
    Performance of the new feature (cache use, algorithm complexity, etc)  
        Bonus goal: any performance gains in the existing feature  
    Correctness and reliability of the new feature  
        Bonus goal: making the existing feature work with long.json   
    Abstraction, code-reuse and testability of the new feature  
        Bonus goal: adding test coverage to existing feature  