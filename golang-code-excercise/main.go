package main

import (
	"encoding/json"
	"fmt"
	"os"
	"strconv"
	"strings"
)

func main() {
	if len(os.Args) < 2 || os.Args[1] != "avgtimes" {
		panic("please run with arguments `avgtimes <filename>`")
	}

	data := make([]map[string]string, 0)
	if f, err := os.Open(os.Args[2]); err != nil {
		panic(err.Error())
	} else {
		if err := json.NewDecoder(f).Decode(&data); err != nil {
			panic(err.Error())
		}
	}

	counts := make(map[string][]int, 0)
	for _, k := range data {
		timing, err := strconv.ParseInt(strings.TrimSuffix(k["timing"], "ms"), 10, 64)
		if err != nil {
			panic(err.Error())
		}
		counts[k["category"]] = append(counts[k["category"]], int(timing))
	}
	for cat, times := range counts {
		s := 0.0
		for _, t := range times {
			s += float64(t)
		}
		fmt.Printf("cat %s\tavg %fms\n", cat, s/float64(len(times)))
	}
}
